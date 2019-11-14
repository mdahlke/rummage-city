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
use Webpatser\Uuid\Uuid;

class SavedListingController {

    public function __construct() {
        // parent::__construct();
    }

    public function save(Request $request, Listing $listing) {
        $user = Auth::user();

        $user->savedListing()->attach($listing->id, ['id' => Uuid::generate()->string]);

        return response()->json(true);
    }

    public function remove(Request $request, User $user, Listing $listing) {
        $user = Auth::user();

        $user->savedListing()->detach($listing->id);

        return response()->json(true);
    }

}
