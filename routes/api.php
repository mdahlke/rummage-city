<?php

use App\Http\Controllers\DashboardController;
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

Route::post('login', 'AuthController@login');
Route::post('signup', 'AuthController@signup');

Route::middleware('auth:api')->group(function () {
    Route::get('logout', 'AuthController@logout');
    Route::get('user', 'AuthController@user');

    //	Route::match(['get', 'post'], 'graphql/{schema}', '\Rebing\GraphQL\GraphQLController@query');
//	Route::get('graphql/{schema}', '\Rebing\GraphQL\GraphQLController@query');
//    Route::post('login', 'Auth\LoginController@login');
//    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::get('user/listings', 'DashboardController@userListings');
    Route::get('user/listings/saved', 'DashboardController@savedListings');
    Route::get('listing/:listing/edit', 'ListingController@edit');
});


Route::middleware('api')->group(function () {
    Route::get('listings/recent', ListingsRecentController::class . '@index');

});
