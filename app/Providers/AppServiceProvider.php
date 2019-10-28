<?php

namespace App\Providers;

use App\Listing;
use App\ListingImage;
use App\Observers\ImageObserver;
use App\Observers\ListingObserver;
use App\Observers\UserObserver;
use App\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		User::observe(UserObserver::class);
		Listing::observe(ListingObserver::class);
		ListingImage::observe(ImageObserver::class);
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}
}
