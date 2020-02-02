<?php

namespace App\Providers;

use App\Listing;
use App\ListingImage;
use App\Observers\ImageObserver;
use App\Observers\ListingObserver;
use App\Observers\UserObserver;
use App\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Telescope\TelescopeServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        if (App::environment('production')) {
            URL::forceScheme('https');
        }
        User::observe(UserObserver::class);
        Listing::observe(ListingObserver::class);
        ListingImage::observe(ImageObserver::class);

        Passport::ignoreMigrations();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->app->register(TelescopeServiceProvider::class);

        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
