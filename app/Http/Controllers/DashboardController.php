<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class DashboardController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $data = [];
        $user = Auth::user();

        $activeListings = Cache::remember('user:listings:active:' . $user->id, 3600, function () use ($user) {
            return $user->activeListing ?? [];
        });

        $inactiveListings = Cache::remember('user:listings:inactive:' . $user->id, 3600, function () use ($user) {
            return $user->inactiveListings ?? [];
        });

        $savedListings = Cache::remember('user:listings:saved:' . $user->id, 3600, function () use ($user) {
            return $user->savedListing ?? [];
        });

        $data['activeListings'] = $activeListings;
        $data['inactiveListings'] = $inactiveListings;
        $data['savedListings'] = $savedListings;

        return view('dashboard.home', $data);
    }

    public function userListings(Request $request) {
        $user = $request->user();

        $listings = Cache::remember('user:listings:active:' . $user->id, 3600, function () use ($user) {
            return $user->activeListing ?? [];
        });

        return response()->json($listings);
    }

    public function savedListings(Request $request) {
        $user = $request->user();

        $listings = Cache::remember('user:listings:saved:' . $user->id, 3600, function () use ($user) {
            return $user->savedListing ?? [];
        });

        return response()->json($listings);
    }
}
