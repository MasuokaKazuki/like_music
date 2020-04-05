<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\SimilarArtist;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
	/**
	 * アーティストの似たアーティストの曲を返却する。
	 */
	public function index($artistName){
	}

	/**
	 * アーティストの似たアーティスト情報を更新する。
	 */
	public function update(Request $request){
		$artistName = $request->input('name');
		$artist = new Artist();
		$artist->doUpdateWithSimilarArtist($artistName);
	}
}
