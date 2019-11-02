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
use App\Providers\MapboxProvider;
use App\User;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ListingController extends Controller {

    public function __construct() {
    }

    public function index(Request $request, $location = null) {
        $data = [];
        $geocode = $request->get('geocode');

        $searchState = (object)[
            'bounds' => (object)[
                'lat' => false,
                'lng' => false,
            ],
            'listing' => false,
            'zoom' => false,
            'pitch' => false,
            'bearing' => false,
        ];

        /** @var Builder $listings */
        $builder = Listing::query()
            ->with('activeDate')
            ->with('image')
            ->whereHas('activeDate');

        if ($geocode) {
            $searchState->bounds->lat = $geocode->getCenter()[1];
            $searchState->bounds->lng = $geocode->getCenter()[0];

            $searchState->zoom = 12;

            $builder->where(function ($q) use ($location) {
                /** @var Builder $q */
                $q->orWhere('street_name', 'LIKE', $location)
                    ->orWhere('address', 'LIKE', $location)
                    ->orWhere('city', 'LIKE', $location)
                    ->orWhere('state', 'LIKE', $location)
                    ->orWhere('postcode', 'LIKE', $location);
            });

//            dd($geocode, $searchState, json_encode($searchState));
        }

        $listings = $builder->paginate(10);

        $data['listings'] = $listings;
        $data['searchState'] = $request->query('searchState', json_encode($searchState));

        return view('listings.index', $data);
    }

    public function geo() {
        $nw = [
            'lat' => 44.24770009175438,
            'lng' => -88.94442805641779,
        ];
        $se = [
            'lat' => 43.247745933091565,
            'lng' => -87.95291194335938,
        ];

        dd(json_encode(['nw' => $nw, 'se' => $se]));
        /** @var Builder $listings */
        DB::enableQueryLog();
        $listings = Listing::query()
            ->where('latitude', '>', $se['latitude'])
            ->where('latitude', '<', $nw['latitude'])
            ->where('longitude', '<', $se['longitude'])
            ->where('longitude', '>', $nw['longitude'])
            //			->toSql();
            ->get();

        dd(DB::getQueryLog(), $listings);
    }

    public function view(Request $request, Listing $listing) {
        $data = [];

        $data['listing'] = $listing;

        return view('listings.view', $data);
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
        $route = '';
        if ($listing->exists) {
            $route = route('user.listing.edit', ['listing' => $listing->id]);
        } else {
            $route = route('user.listing.new');
        }

        if ($listing->exists && $user->id != $listing->user->id) {
            return view('error.403');
        }

        if ($request->isMethod('post')) {
            $listing->fill($request->only($listing->fillable));
            $listing->ip_address = $_SERVER['REMOTE_ADDR'];

            $saved = $user->listing()->save($listing);

            if ($saved) {
                $starts = $request->input('start');
                $ends = $request->input('end');

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
                $removeImages = $request->input('remove_images');
                $removeImages = json_decode($request->input('remove_images'));
//				dd($removeImages);
                foreach ($removeImages as $remove) {
                    $image = ListingImage::query()->where('id', $remove)->first();

                    if ($image->exists) {
                        $image->delete();
                    }
                }
            }

            return redirect(route('dashboard'));
        }

        return view('listings.edit', ['listing' => $listing, 'route' => $route]);
    }

}
