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

Route::group(['namespace' => 'Api'], function () {
	Route::prefix('v1')->group(function () {
		Route::post('/artist'                          , 'ArtistController@update'           );
		Route::get ('/artist/{name}/similarTrack'      , 'SimilarArtistTrackController@index');
		Route::get ('/lastfm/{artist}/similarTrack'    , 'LastfmController@index');
		Route::get ('/youtube/{artist}/{track}'        , 'YoutubeController@index');
	});
});