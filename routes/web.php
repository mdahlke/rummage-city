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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();


Route::middleware('web')
     ->middleware('auth')->group(function () {
		Route::match(['get', 'post'], 'listings/new/', 'ListingController@edit')->name('listings.new');
		Route::match(['get', 'post'], 'listings/{listing}/edit/', 'ListingController@edit')->name('listings.edit');

		Route::post('account/{user}/saved/{listing}/save/', 'UserController@saveListing')->name('user.saveListing');

		Route::get('dashboard/', 'DashboardController@index')->name('dashboard');
	});

Route::middleware('web')->group(function () {
	Route::get('/', function () {
		return view('home.welcome');
	})->name('home');

	Route::get('/listings/', 'ListingController@index')->name('listings.browse');
	Route::get('/listings/{listing}/view', 'ListingController@view')->name('listings.view');
});


//Route::get('/listings/{coords?}/{zoom?}', function($coords = '', $zoom = 8){
//	return View::make('listings', ['coords' => $coords, 'zoom' => $zoom]);
//})->where(['zoom' => '[0-9]{2}'])->name('listings.browse');


