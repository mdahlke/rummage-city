<?php

namespace App\Observers;

use App\Listing;
use App\User;
use Illuminate\Support\Facades\Auth;

class ListingObserver {
    public function retrieved(Listing $listing) {
        if (!Auth::guest()) {
            /** @var User $user */
            $user = Auth::user();
            $listing->setSaveUrl(route('user.saveListing', [
                'user' => Auth::user(),
                'listing' => $listing->id,
            ]));

            $listing->setRemoveSavedUrl(route('user.removeSavedListing', [
                'user' => $user,
                'listing' => $listing->id,
            ]));

            $listing->setIsSaved($user->hasSavedListing($listing->id));
        } else {
            $listing->setSaveUrl(route('login'));
            $listing->setRemoveSavedUrl(route('login'));
        }
    }

    /**
     * Handle the listing "created" event.
     *
     * @param \App\Listing $listing
     * @return void
     */
    public function created(Listing $listing) {
        //
    }

    /**
     * Handle the listing "updated" event.
     *
     * @param \App\Listing $listing
     * @return void
     */
    public function updated(Listing $listing) {
        //
    }

    /**
     * Handle the listing "deleted" event.
     *
     * @param \App\Listing $listing
     * @return void
     */
    public function deleted(Listing $listing) {
        //
    }

    /**
     * Handle the listing "restored" event.
     *
     * @param \App\Listing $listing
     * @return void
     */
    public function restored(Listing $listing) {
        //
    }

    /**
     * Handle the listing "force deleted" event.
     *
     * @param \App\Listing $listing
     * @return void
     */
    public function forceDeleted(Listing $listing) {
        //
    }
}
