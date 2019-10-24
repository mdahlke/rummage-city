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

Route::middleware(['auth', 'web'])->group(function () {
	Route::prefix('listings')->group(function () {
		Route::name('listings.')->group(function () {
			Route::match(['get', 'post'], 'new', 'ListingController@edit')->name('new');
			Route::match(['get', 'post'], '{listing}/edit', 'ListingController@edit')->name('edit');
		});
	});

	Route::post('account/{user}/saved/{listing}/save', 'UserController@saveListing')->name('user.saveListing');

	Route::get('dashboard', 'DashboardController@index')->name('dashboard');
});

Route::middleware('web')->group(function () {
	Route::get('/', function () {
		$schemas = config('graphql.schemas');
		dd($schemas);
		return view('home.welcome');
	})->name('home');

	Route::prefix('listings')->group(function () {
		Route::name('listings.')->group(function () {
			Route::get('/', 'ListingController@index')->name('browse');
			Route::get('{listing}/view', 'ListingController@view')->name('view');
		});
	});
});

Route::match(['get', 'post'], 'graphql/{schema}', '\Rebing\GraphQL\GraphQLController@query');

//Route::get('/listings/{coords?}/{zoom?}', function($coords = '', $zoom = 8){
//	return View::make('listings', ['coords' => $coords, 'zoom' => $zoom]);
//})->where(['zoom' => '[0-9]{2}'])->name('listings.browse');


