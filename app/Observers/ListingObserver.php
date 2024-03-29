<?php

namespace App\Observers;

use App\Listing;
use App\Notifications\ListingCreated;
use App\Notifications\ListingUpdated;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ListingObserver {
    use Queueable;

    public function retrieved(Listing $listing) {
        if (!Auth::guest()) {
            /** @var User $user */
            $user = Auth::user();

            $listing->setSaveUrl(route('saveListing', [
                'listing' => $listing->id,
            ]));

            $listing->setRemoveSavedUrl(route('removeSavedListing', [
                'listing' => $listing->id,
            ]));

            $listing->setIsSaved($user->hasSavedListing($listing->id));
        }
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
