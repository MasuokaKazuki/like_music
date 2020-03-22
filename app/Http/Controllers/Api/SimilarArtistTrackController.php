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
		$time_start = microtime(true);

		$lastfm = new Lastfm();
		//$tmp = $this->similarArtistTracksAddMovieUrl($lastfm->getSimilarArtistTrack($artistName),1);

		$similarArtist = new SimilarArtist();
		$tmp = $similarArtist->getSimilarArtistTrack($artistName);

		$time = microtime(true) - $time_start;
		echo "<p>youtubeの取得と結合の取得{$time} 秒</p>";

		echo "<pre>";
		print_r($tmp);
		echo "</pre>";
	}

	/**
	 * アーティストの似たアーティスト情報を更新する。
	 */
	public function update($artistName){
		$time_start = microtime(true);

		$artist = new Artist();
		$tmp = $artist->doUpdateWithSimilarArtist($artistName);
		
		$time = microtime(true) - $time_start;
		echo "<p>youtubeの取得と結合の取得{$time} 秒</p>";

		echo "<pre>";
		print_r($tmp);
		echo "</pre>";
	}
}
