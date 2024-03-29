<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('auth', 'UserController@auth');


Route::middleware('api')->group(function () {
    Route::get('/listings/recent', 'ListingsRecentController@index');

    Route::post('/login', 'AuthController@login');
    Route::post('/signup', 'AuthController@signup');


    Route::get('renew-csrf', function () {
        return response()->json(csrf_token());
    });

});


Route::middleware('auth:api')->group(function () {
    Route::get('/logout', 'AuthController@logout');
    Route::get('/user', 'AuthController@user');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/user/{user}/edit', 'UserController@update');
    Route::get('/user/listings/saved', 'DashboardController@savedListings');
    Route::get('/user/listings', 'DashboardController@userListings');

    Route::apiResource('/listings', 'ListingController');

    Route::post('/listing-image/{image}/remove', 'ListingImageController@remove')->name('listings.image.remove');
    Route::post('/listing-images/remove', 'ListingImageController@removeBulk')->name('listings.images.remove');

    Route::get('/saved/{listing}/save', 'SavedListingController@edit')->name('saveListing');
    Route::get('/ saved/{listing}/remove', 'SavedListingController@edit')->name('removeSavedListing');
});
