<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\SimilarArtist;
use App\Libraries\Lastfm;
use App\Libraries\GoogleCustomSearch;
use Illuminate\Http\Request;

class SimilarArtistTrackController extends Controller
{
	/**
	 * アーティストの似たアーティストの曲を返却する。
	 */
	public function index($artistName){
		$similarArtist = new SimilarArtist();
		$tmp = $similarArtist->getSimilarArtistTrack($artistName);
		return response()->json(['similar_artist_track' => $tmp]);
	}
}
