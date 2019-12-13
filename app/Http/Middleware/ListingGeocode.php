<?php

namespace App\Http\Middleware;

use App\Geocode;
use App\Http\Services\MapboxService;
use App\MapboxFeature;
use Closure;
use Illuminate\Http\Request;

class ListingGeocode {
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        // everything has already been geocoded via the JS API
        // we just need to build the Geocode class
        if ($request->query('place_id')) {
            $geocode = new Geocode();

            // all of this information is set via JS after a successful geocode and selection by the user
            $feature = [
                'place_id' => $request->query('place_id'),
                'place_name' => $request->query('place_name'),
                'matching_place_name' => $request->query('matching_place_name'),
                'bbox' => json_decode($request->query('bbox')),
                'center' => json_decode($request->query('center')),
                'geometry' => json_decode($request->query('geometry')),
            ];

            $mapboxFeature = new MapboxFeature($feature);
            $geocode->addFeature($mapboxFeature);

            $route = route('listings.browse', ['location' => str_slug($geocode->feature()->getAttribute('place_name'), '-')]);
            return redirect($route);
        } else {
            if ($location = $request->query('q')) {
                $geocode = MapboxService::forwardGeocode($location);

                if ($geocode) {
                    $route = route('listings.browse', ['location' => str_slug($geocode->feature()->getAttribute('place_name'), '-')]);
                    return redirect($route);
                }

            } else if ($location = $request->route('location')) {
                $geocode = MapboxService::forwardGeocode($location);

                $request->request->add(['geocode' => $geocode]);
            }
        }

        return $next($request);
    }
}
