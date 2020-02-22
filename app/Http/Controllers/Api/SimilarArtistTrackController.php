<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\SimilarArtist;
use App\Libraries\Lastfm;
use Illuminate\Http\Request;

class SimilarArtistTrackController extends Controller
{
	/**
	 * アーティストの似たアーティストの曲を返却する。
	 */
	public function index(){
		// 引数を受け取って、情報を取得して表示
		$tmp = date('Y-m-d', strtotime('-3 month'));
		//$this->check("BUMP OF CHICKEN");
		$artist = new Artist();
		var_dump($artist->doUpdateWithSimilarArtist("TOFUBEATS"));
		//$artist = Artist::where('name',"BUMP OF CHICKEN")->first();
		//var_dump($artist->id);
		//return $tmp;
	}
}
