<?php

namespace App\Observers;

use App\Listing;
use App\Notifications\ListingCreated;
use App\Notifications\ListingUpdated;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ListingObserver {
    public function retrieved(Listing $listing) {
        if (!Auth::guest()) {
            /** @var User $user */
            $user = Auth::user();

            $listing->setSaveUrl(route('user.listing.saveListing', [
                'listing' => $listing->id,
            ]));

            $listing->setRemoveSavedUrl(route('user.listing.removeSavedListing', [
                'listing' => $listing->id,
            ]));

            $listing->setIsSaved($user->hasSavedListing($listing->id));
        } else {
            $listing->setSaveUrl(route('login'));
            $listing->setRemoveSavedUrl(route('login'));
        }

        $listing->setViewUrl(route('listings.view', [
            'address' => str_slug($listing->address),
            'listing' => $listing->id,
        ]));

    }

    /**
     * Handle the listing "created" event.
     *
     * @param \App\Listing $listing
     * @return void
     */
    public function created(Listing $listing) {
        $for = now()->addMinutes(10);
        $listing->user->notify((new ListingCreated($listing))->delay($for));

        Cache::forget('user:listings:active:' . $listing->user->id);
        Cache::forget('user:listings:inactive:' . $listing->user->id);
    }

    /**
     * Handle the listing "updated" event.
     *
     * @param \App\Listing $listing
     * @return void
     */
    public function updated(Listing $listing) {
        $for = now()->addMinutes(1);
        $listing->user->notify((new ListingUpdated($listing))->delay($for));

        Cache::forget('user:listings:active:' . $listing->user->id);
        Cache::forget('user:listings:inactive:' . $listing->user->id);
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
