<?php

namespace App\Http\Controllers;

use Auth;
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
        $user = Auth::user();

        $saved = $user->savedListing;

        $data['activeListings'] = $user->activeListing ?? [];
        $data['inactiveListings'] = $user->inactiveListings ?? [];
        $data['savedListings'] = $saved;

        return view('dashboard.home', $data);
    }
}
