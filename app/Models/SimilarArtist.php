<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Lastfm;
use App\Libraries\GoogleCustomSearch;
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
			$hasApiArtistSimilar = isset($apiArtistSimilar["similarartists"]["artist"]);

			if( $hasApiArtistSimilar ){
				$arrApiArtistSimilar = $apiArtistSimilar["similarartists"]["artist"];
				SimilarArtist::where('artist_id',$artist->id)->delete();

				foreach( $arrApiArtistSimilar as $apiData ){
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

	/**
	 * 似たアーティストの情報を取得する(DBに格納のデータから)
	 */
	public function getSimilarArtistTrack($artistName=""){
		$similarArtistTracks = array();

		$artist = Artist::where('name',$artistName)->first();
		if( $artist != NULL ){
			$arrSimilarArtist = $artist->similarArtist;

			foreach($arrSimilarArtist as $similarArtist){
				$artist = Artist::find($similarArtist->similar_artist_id);
				$arrTracks = $artist->traks;

				foreach($arrTracks as $track){
					$similarArtistTracks[] = array('artist'=>$artist->name, 'track'=>$track->name, 'video_id'=>$track->youtube_video_id);
				}
			}

			if(is_array($similarArtistTracks)) shuffle($similarArtistTracks);
		}
		return $similarArtistTracks;
	}

	/**
	 * 似たアーティストの曲にyoutubeの動画URL情報を付与する。
	 */
	public function similarArtistTracksAddMovieUrl(array $similarArtistTracks,int $limit=10){
		$time_start = microtime(true);

		$result = array();
		$cnt = 1;
		$cse = new GoogleCustomSearch();

		foreach($similarArtistTracks as $data){
			$time = microtime(true) - $time_start;
			echo "<p>getTrackMovieUrl - start{$time} 秒</p>";
			$url = $cse->getTrackMovieUrl($data['artist'],$data['track']);
			$time = microtime(true) - $time_start;
			echo "<p>getTrackMovieUrl - end{$time} 秒</p>";

			if($url != NULL){
				$data += array('url' => $url);
				$result[] = $data;
				$cnt++;
				if( $cnt > $limit ) break;
			}
		}

		return $result;
	}
}