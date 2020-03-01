<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\SimilarArtist;
use App\Libraries\Lastfm;
use App\Libraries\YouTube;
use Illuminate\Http\Request;

class SimilarArtistTrackController extends Controller
{
	/**
	 * 似たアーティストの情報を取得する(LastFmのAPIより)
	 */
	public function getSimilarArtistTrack($artistName=""){
		$similarArtistTracks = $this->getSimilarArtistTrackByDB($artistName);

		if(empty($similarArtistTracks)){
			$lastfm = new Lastfm();
			$similarArtistTracks = $lastfm->createSimilarArtistTrack($artistName);
		}

		return $similarArtistTracks;
	}

	/**
	 * 似たアーティストの情報を取得する(DBに格納のデータから)
	 */
	private function getSimilarArtistTrackByDB($artistName=""){
		$similarArtistTracks = array();

		$artist = Artist::where('name',$artistName)->first();
		$arrSimilarArtist = $artist->similarArtist;

		foreach($arrSimilarArtist as $similarArtist){
			$artist = Artist::find($similarArtist->similar_artist_id);
			$arrTracks = $artist->traks;
			foreach($arrTracks as $track){
				$similarArtistTracks[] = array('artist'=>$artist->name, 'track'=>$track->name);
			}
		}

		if(is_array($similarArtistTracks)) shuffle($similarArtistTracks);
		return $similarArtistTracks;
	}

	private function similarArtistTracksAddMovie(array $similarArtistTracks,int $limit=10){
		$result = array();
		$cnt = 1;
		foreach($similarArtistTracks as $data){
			$youtube = new YouTube();
			//$movieId = $youtube->getTrackMovieId($data['artist'],$data['track']);
			$movieId = "test";
			if($movieId != NULL){
				$data += array('movieId' => $movieId);
				$result[] = $data;
				$cnt++;
				if( $cnt > $limit ) break;
			}
		}
		return $result;
	}

	/**
	 * アーティストの似たアーティストの曲を返却する。
	 */
	public function index($artistName){
		// 引数を受け取って、情報を取得して表示
		//echo "<pre>";
		//print_r();
		//echo "</pre>";
		$tmp = $this->similarArtistTracksAddMovie($this->getSimilarArtistTrack($artistName),5);
		//$tmp = $this->getSimilarArtistTrack($artistName);

		echo "<pre>";
		print_r($tmp);
		echo "</pre>";
	}
}
