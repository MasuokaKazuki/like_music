<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Lastfm;
use App\Models\Artist;

class SimilarArtist extends Model
{
	protected $table = 'similar_artist';

	/**
	 * 似たアーティストをアップデートする。
	 */
	public function doUpdate($artistName=""){
		$artist = Artist::where('name',$artistName)->first();

		if( $artist != NULL ){
			$lastfm = new Lastfm();
			$apiArtistSimilar = $lastfm->getArtistSimilar($artistName);
			$hasApiArtistSimilar = (isset($apiArtistSimilar["similarartists"]["artist"])) ? true : false ;

			if( $hasApiArtistSimilar ){
				$arrApiArtistSimilar = $apiArtistSimilar["similarartists"]["artist"];
				SimilarArtist::where('artist_id',$artist->id)->delete();

				foreach($arrApiArtistSimilar as $apiData){
					$artistModel = new Artist();
					$artistModel->doUpdate($apiData["name"]);
					$similarArtistData = Artist::where('name',$apiData["name"])->first();

					if( $similarArtistData != NULL ){
						$similarArtist = new SimilarArtist();
						$similarArtist->artist_id         = $artist->id;
						$similarArtist->similar_artist_id = $similarArtistData->id;
						$similarArtist->save();
					}
				}
			}
		}
	}

	/**
	 * 指定したアーティスト名が、似たアーティストのデータを持っているか判定する。
	 */
	public function hasSimilarArtist($artistName=""){
		$result = false;
		$artistModel = new Artist();
		$artistId = $artistModel->getArtistIdByName($artistName);

		if( $artistId != NULL ){
			$result = SimilarArtist::where('artist_id',$artistId)->exists();
		}
		return $result;
	}
}