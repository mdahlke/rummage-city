<?php

namespace App\Http\Middleware;

use App\Http\Services\MapboxService;
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


        return $next($request);
    }
}
