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

        $activeListings = Cache::remember('user:listings:active', 60, function () use ($user) {
            return $user->activeListing ?? [];
        });

        $inactiveListings = Cache::remember('user:listings:inactive', 60, function () use ($user) {
            return $user->inactiveListings ?? [];
        });

        $savedListings = Cache::remember('user:listings:saved', 60, function () use ($user) {
            return $user->savedListing ?? [];
        });

        $data['activeListings'] = $activeListings;
        $data['inactiveListings'] = $inactiveListings;
        $data['savedListings'] = $savedListings;

        return view('dashboard.home', $data);
    }
}
