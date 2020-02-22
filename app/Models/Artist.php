<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SimilarArtist;
use App\Models\Track;
use App\Libraries\Lastfm;

class Artist extends Model
{
	protected $table = 'artist';

	/**
	 * アップデートすべきか判定する。
	 * データが存在しない、または1か月以内のデータが存在しなければ、アップデート（インサート）する。
	 */
	public function shouldUpdate($artistName=""){
		if( $artistName=="" ) return false;
		$exists = Artist::where('name',$artistName)
						->whereDate('updated_at', '>', date('Y-m-d', strtotime('-1 month')))
						->exists();
		return ( $exists ) ? false : true ;
	}

	public function doUpdateWithSimilarArtist($artistName=""){
		$this->doUpdate($artistName, true);
	}

	/**
	 * アップデート実行する。
	 * アーティスト情報をアップデートするとき以下の情報も同時にアップデートする。
	 * ・Track ... 楽曲情報
	 * ・SimilarArtist ... 似たアーティスト
	 * @param $artistName アーティスト名
	 * @param $withSimilar 似たアーティストも更新するかどうか
	 */
	public function doUpdate($artistName="",$withSimilar=false){
		$lastfm = new Lastfm();
		$apiArtist = $lastfm->getArtist($artistName);

		if( !isset($apiArtist["error"]) && isset($apiArtist["artist"]["name"]) ){
			$apiArtistName = $apiArtist["artist"]["name"];
			if( $this->shouldUpdate($apiArtistName) ){
				$artist = Artist::where('name',$artistName)->first();
	
				if( $artist == NULL ) $artist = new Artist();
				$artist->name = $apiArtistName;
				$artist->save();

				$track = new Track();
				$track->doUpdate($apiArtistName);

				$artist->touch();
			}

			$similarArtist = new SimilarArtist();

			if( $withSimilar && ( $this->shouldUpdate($apiArtistName) || !$similarArtist->hasSimilarArtist($apiArtistName) ) ){
				$similarArtist->doUpdate($apiArtistName);
			}
		}
	}

	/**
	 * アーティスト名からIDを索引する。
	 */
	public function getArtistIdByName($artistName=""){
		$result = NULL;
		$artist = Artist::where('name',$artistName)->first();
		if( $artist != NULL ) $result = $artist->id;
		return $result;
	}
}