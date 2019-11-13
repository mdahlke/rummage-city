<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $user = \Auth::user();

        $saved = $user->savedListing;

//        dd($saved->listing);

        $data['activeListings'] = $user->activeListing ?? [];
        $data['savedListings'] = $saved;

        return view('dashboard.home', $data);
    }
}
