<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Middleware\ListingGeocode;
use App\Listing;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//Auth::routes([
//    'register',
//    'reset',
//    'confirm',
//    'verify',
//]);


Route::match(['get', 'post'], 'graphql/{schema}', '\Rebing\GraphQL\GraphQLController@query');

//Route::middleware(['web'])->group(function () {
// catches all other routes and sends them to the Vue app
Route::any('/{any}', 'SpaController@index')->where('any', '.*');
//});


//Route::middleware(['auth', 'web'])->group(function () {
//
//    Route::prefix('dashboard')->group(function () {
//        Route::get('/', 'DashboardController@index')->name('dashboard');
//
//        Route::prefix('listings')->group(function () {
//            Route::name('user.listing.')->group(function () {
//                Route::match(['get', 'post'], '/new', 'ListingController@edit')->name('new');
//                Route::match(['get', 'post'], '/{listing}/edit', 'ListingController@edit')->name('edit');
//                Route::post('saved/{listing}/save', 'SavedListingController@save')->name('saveListing');
//                Route::delete('saved/{listing}/remove', 'SavedListingController@remove')->name('removeSavedListing');
//            });
////        Route::get('listing-image/{image}/remove', 'ListingImageController@remove')->name('listings.image.remove');
//        });
//
//        Route::name('user.')->group(function () {
//            Route::get('settings', 'UserController@settings')->name('settings');
//        });
//    });
//});
//
Route::middleware('web')->group(function () {
    Route::get('/', function () {
        return view('home.home');
    })->name('home');

    Route::prefix('listings')->group(function () {
        Route::name('listings.')->group(function () {
            Route::middleware(ListingGeocode::class)->group(function () {
                Route::get('/{location?}', 'ListingController@index')->name('browse');
            });
            Route::get('geo', 'ListingController@geo')->name('geo');
            Route::get('{address?}/{listing}/view', 'ListingController@view')->name('view');
        });
    });

    Route::get('login-form', 'Auth\LoginController@showLoginForm')->name('login.form');


});
