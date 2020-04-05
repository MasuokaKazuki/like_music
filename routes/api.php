<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});
Route::group(['namespace' => 'Api'], function () {
	Route::prefix('v1')->group(function () {
		Route::get ('/artist/{name}              ', 'ArtistController@index'            );
		Route::post('/artist'                     , 'ArtistController@update'           );
		Route::get ('/artist/{name}/similarTrack' , 'SimilarArtistTrackController@index');
	});
});