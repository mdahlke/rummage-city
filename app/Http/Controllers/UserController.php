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

use App\Notifications\UserEmailChange;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;

class UserController {

    public function __construct() {
        // parent::__construct();
    }

    public function auth(Request $request) {
        $authenticated = false;
        $user = Auth::user();
        $guest = Auth::guest();

        if ($user) {
            $authenticated = true;
        }

        return response()->json([
            'user' => $user,
            'guest' => $guest,
            'authenticated' => $authenticated,
        ]);
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


    public function update(\Illuminate\Http\Request $request, User $user) {
        $updatingEmail = false;
        $oldEmail = false;
        $user->name = $request->input('name');

        if ($user->email !== $request->input('email')) {
            $email = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL);

            if ($email) {
                $user->email = $email;
                $updatingEmail = true;
                $oldEmail = $request->input('email');
            }
        }

        if ($updatingEmail && $oldEmail) {
            $user->notify(new UserEmailChange($user, $oldEmail));
//            Mail::to($oldEmail)->send(new UserEmailChange($user, $oldEmail));
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Account updated.'
        ]);
    }


}
