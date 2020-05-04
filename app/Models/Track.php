<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Lastfm;
use App\Libraries\GoogleCustomSearch;
use App\Models\Artist;

class Track extends Model
{
    protected $table = 'track';

    /**
	 * トップトラックをアップデートする。
	 */
	public function doUpdate($artistName=""){
		$artist = Artist::where('name',$artistName)->first();

		if( $artist != NULL ){
			$lastfm = new Lastfm();
			$apiArtistTrack = $lastfm->getTrack($artistName,5);
			$hasApiArtistTrack = (isset($apiArtistTrack["toptracks"]["track"])) ? true : false ;

			if( $hasApiArtistTrack ){
				$arrApiArtistTrack = $apiArtistTrack["toptracks"]["track"];

				Track::where('artist_id',$artist->id)->delete();
				$cse = new GoogleCustomSearch();

                foreach($arrApiArtistTrack as $apiData){
					$trackName = $apiData["name"];
                    $track = new Track();
                    $track->artist_id  = $artist->id;
                    $track->name       = $trackName;
                    $track->play_count = $apiData["playcount"];

					$trackMovie = $cse->getTrackMovieData($artistName,$trackName);
					if(isset($trackMovie)){
						$videoId    = $trackMovie['id'];
						$thumbnail  = $trackMovie['thumbnail'];
					}else{
						continue;
					}

					$track->youtube_video_id  = $videoId;
					$track->youtube_thumbnail = $thumbnail;
                    $track->save();
				}
			}
		}
	}
}