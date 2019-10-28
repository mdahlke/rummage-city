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

use App\Listing;
use App\SavedListing;
use App\User;
use App\Http\Controllers\SavedListingController as Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class SavedListingController {

    public function __construct() {
        // parent::__construct();
    }

    public function save(Request $request, User $user, Listing $listing) {

        $user->savedListing()->updateOrCreate([
            'listing_id' => $listing->id,
        ]);

        return response()->json([
            'name' => 'Abigail',
            'state' => 'CA'
        ]);
    }

    public function remove(Request $request, User $user, Listing $listing) {
        SavedListing::where('listing_id', $listing->id)
            ->where('user_id', $user->id)
            ->delete();

        return response()->json([
            'name' => 'Abigail',
            'state' => 'CA'
        ]);
    }

}
