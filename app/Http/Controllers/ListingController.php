<?php
/**
 * File: ListingController.php
 * Date: 10/16/19
 * Time: 10:10 AM
 *
 * @package rummagecity.dev
 * @author Michael Dahlke <mdahlke@wisnet.com>
 */

namespace App\Http\Controllers;

use App\Http\Middleware\Cors;
use App\Http\Services\MapboxService;
use App\Listing;
use App\ListingDate;
use App\ListingImage;
use App\MapboxFeature;
use App\Notifications\ListingCreated;
use App\Providers\MapboxProvider;
use App\User;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ListingController extends Controller {

    public function __construct() {
    }

    public function index(Request $request, $location = null) {
        $data = [];
        $geocode = $request->get('geocode');
        $searchState = $request->query('searchState');
        $searchStateLoaded = false;
        $term = '';

        if ($searchState) {
            $searchStateLoaded = true;
            $searchState = json_decode($searchState);
        } else {
            $searchState = (object)[
                'map' => (object)[
                    'center' => (object)[],
                    'zoom' => false,
                    'pitch' => false,
                    'bearing' => false,
                ],
                'listing' => false,
                'term' => false,
                'filter' => []
            ];
        }

        dd($searchState);

        /** @var Builder $listings */
        $builder = Listing::query();

        $user = \Auth::user();
        if ($user && isset($searchState->filter['saved'])) {
            $builder = $user->savedListing();
        }

        $builder->with('activeDate')
            ->with('image')
            ->whereHas('activeDate');

        if (!$searchStateLoaded && $geocode) {
            $searchState->map->center->lat = $geocode->getCenter()[1];
            $searchState->map->center->lng = $geocode->getCenter()[0];
            /** @var MapboxFeature $feature */
            $feature = $geocode->features[0];

            $searchState->map->zoom = 12;

            $builder->where(function ($q) use ($feature) {
                $q->whereBetween('longitude', [$feature->getWest(), $feature->getEast()])
                    ->whereBetween('latitude', [$feature->getSouth(), $feature->getNorth()]);
            });

            $term = $feature->place_name;
        } else if ($searchStateLoaded) {
            $builder->where(function ($q) use ($searchState) {
                $q->whereBetween('longitude', [$searchState->map->bounds->_sw->lng, $searchState->map->bounds->_ne->lng])
                    ->whereBetween('latitude', [$searchState->map->bounds->_sw->lat, $searchState->map->bounds->_ne->lat]);
            });
        }

        $builder->limit(100000);

        $queryHash = md5($builder->toSql());


        $listings = Cache::remember('listings:query', 60, function () use ($builder) {
            return $builder->get()->toArray();
        });

        $searchState->term = $term;

        $data['listings'] = $listings;
        $data['searchState'] = json_encode([
            'url' => route('listings.browse'),
            'query' => $searchState,
        ]);

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @param string $address
     * @param Listing $listing
     * @return Factory|View
     */
    public function show(Request $request, $address = '', Listing $listing) {
        $data = [];

        $data['listing'] = $listing;

        return view('listings.view', $data);
    }

    public function find(Request $request, Listing $listing) {
        dd($listing);
    }

    /**
     * Create a new listing or Edit an existing one
     *
     * @param Request $request
     * @param Listing|null $listing
     * @return Factory|RedirectResponse|Redirector|View
     * @throws Exception
     */
    public function edit(Request $request, Listing $listing) {
        $user = Auth::user();

//        if ($listing->exists) {
//            $route = route('user.listing.edit', ['listing' => $listing->id]);
//        } else {
//            $route = route('user.listing.new');
//        }

        return view('listings.edit', ['listing' => $listing]);

    }

    public function store(Request $request) {
        $user = Auth::user();
        $listing = new Listing();

        $listing->title = '';
        $listing->description = '';
        $listing->address = '';
        $listing->house_number = '';
        $listing->street_name = '';
        $listing->city = '';
        $listing->state = '';
        $listing->postcode = '';
        $listing->country_code = '';
        $listing->latitude = 0;
        $listing->longitude = 0;
        $listing->ip_address = $_SERVER['REMOTE_ADDR'];

        $user->listing()->save($listing);

        return $this->update($request, $listing);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Listing $listing
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function update(Request $request, Listing $listing) {
        $user = Auth::user();
        $route = '';

        if ($listing->exists && ($user->id != $listing->user->id)) {
            return response()->json([
                'status' => false,
                'code' => 403,
            ]);
        }

        $listing->fill($request->only($listing->fillable));
        $listing->ip_address = $_SERVER['REMOTE_ADDR'];

        $saved = $listing->save();

        if ($saved) {
            $starts = $request->input('start') ?: [];
            $ends = $request->input('end') ?: [];

            // prepare the listing dates
            $dates = [];
            foreach ($starts as $i => $start) {
                if ($start) {
                    $end = $ends[$i];

                    $s = new Carbon($start);
                    $e = new Carbon($end);

                    $dates[] = [
                        'start' => $s,
                        'end' => $e,
                    ];
                }
            }

            if ($dates) {
                // remove all existing dates
                ListingDate::where('listing_id', '=', $listing->id)->delete();
                $listingDates = [];

                foreach ($dates as $date) {
                    $listingDates[] = new ListingDate([
                        'start' => $date['start'],
                        'end' => $date['end'],
                    ]);
                }


                // save all of the dates from the request
                $listing->date()->saveMany($listingDates);
            }

            // upload new images
            if ($request->hasFile('file')) {
                $images = $request->file('file');
                $listingImages = [];

                /** @var UploadedFile $image */
                foreach ($images as $image) {
                    $path = $image->store(User::storagePath());

                    $listingImages[] = new ListingImage([
                        'path' => $path,
                        'name' => $image->getClientOriginalName(),
                    ]);
                }
                $listing->image()->saveMany($listingImages);
            }

            // remove any images that were selected for removal
            $removeImages = json_decode($request->input('remove_images'));

            foreach ($removeImages ?: [] as $remove) {
                $image = ListingImage::query()->where('id', $remove)->first();

                if ($image->exists) {
                    $image->delete();
                }
            }
        }

        return response()->json([
            'status' => true,
            'id' => $listing->id,
        ]);
    }

}
