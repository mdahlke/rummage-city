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
use App\Listing;
use App\ListingDate;
use App\User;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ListingController extends Controller {

	public function __construct() {
	}

	public function index() {
		$data = [];

		/** @var Builder $listings */
		$listings = Listing::query()
		                   ->whereHas('date', function ($query) {
			                   /** @var Builder $query */
			                   $query->whereDate('end', '>=', Carbon::today());
		                   })
			//			->get();
			               ->paginate(10);
		$data['listings'] = $listings;

		return view('listings.index', $data);
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

		if ($listing->exists) {
			$route = route('listings.edit', ['id' => $listing->id]);
		}
		else {
			$route = route('listings.new');
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

				ListingDate::where('listing_id', '=', $listing->id)->delete();

				$listingDates = [];

				foreach ($dates as $date) {
					$listingDates[] = new ListingDate([
						'start' => $date['start'],
						'end' => $date['end'],
					]);
				}

				$listing->date()->saveMany($listingDates);
			}

			return redirect(route('dashboard'));
		}

		return view('listings.edit', ['listing' => $listing, 'route' => $route]);
	}

}
