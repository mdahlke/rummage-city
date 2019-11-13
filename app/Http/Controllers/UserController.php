<?php
/**
 * File: UserController.php
 * Date: 10/22/19
 * Time: 9:56 AM
 *
 * @package rummagecity.dev
 * @author Michael Dahlke <mdahlke@wisnet.com>
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class UserController {

    public function __construct() {
        // parent::__construct();
    }

    public function settings(Request $request) {

        return view('user.settings');
    }

    public function saveListing(Request $request, \App\User $user, \App\Listing $listing) {
        $user->savedListing()->create([
            'listing_id' => $listing->id,
        ]);
    }

    public function removeSavedListing(Request $request, \App\User $user, \App\Listing $listing) {
        $user->savedListing()->delete([
            'listing_id' => $listing->id,
        ]);
    }

}
