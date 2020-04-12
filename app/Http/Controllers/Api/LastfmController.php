<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Libraries\Lastfm;
use Illuminate\Support\Facades\Request;

class LastfmController extends Controller
{
	/**
	 * 指定したアーティストに似たアーティストの情報をJSON形式で返却する
	 */
	public function index($artistName){
		$lastfm = new Lastfm();
		$artistNum      = 15;
		$artistTrackNum = 5;

		if( Request::get('artist_num')       ) $artistNum = Request::get('artist_num');
		if( Request::get('artist_track_num') ) $artistTrackNum = Request::get('artist_track_num');

		$tmp = $lastfm->getSimilarArtistTrack($artistName,$artistNum,$artistTrackNum);
		return response()->json(['similar_artist_track' => $tmp]);
	}
}
