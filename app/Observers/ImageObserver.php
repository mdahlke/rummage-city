<?php

namespace App\Observers;

use App\ListingImage;
use Illuminate\Support\Facades\Storage;

class ImageObserver {

	public function retrieved(ListingImage $image) {
		$image->url = asset('storage/' . $image->path);
	}

	/**
	 * Handle the listing image "created" event.
	 *
	 * @param \App\ListingImage $listingImage
	 * @return void
	 */
	public function created(ListingImage $listingImage) {
		//
	}

	/**
	 * Handle the listing image "updated" event.
	 *
	 * @param \App\ListingImage $listingImage
	 * @return void
	 */
	public function updated(ListingImage $listingImage) {
		//
	}

	/**
	 * Handle the listing image "deleted" event.
	 *
	 * @param \App\ListingImage $listingImage
	 * @return void
	 */
	public function deleted(ListingImage $listingImage) {
		Storage::delete($listingImage->path);
	}

	/**
	 * Handle the listing image "restored" event.
	 *
	 * @param \App\ListingImage $listingImage
	 * @return void
	 */
	public function restored(ListingImage $listingImage) {
		//
	}

	/**
	 * Handle the listing image "force deleted" event.
	 *
	 * @param \App\ListingImage $listingImage
	 * @return void
	 */
	public function forceDeleted(ListingImage $listingImage) {
		//
	}
}
