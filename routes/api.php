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
Route::get('/', function () {
    Route::middleware('auth:api')->group(function () {
        //	Route::match(['get', 'post'], 'graphql/{schema}', '\Rebing\GraphQL\GraphQLController@query');
//	Route::get('graphql/{schema}', '\Rebing\GraphQL\GraphQLController@query');

        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::match(['get', 'post'], 'graphql/{schema}', '\Rebing\GraphQL\GraphQLController@query');

    });
});
