<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Lastfm;
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
			$apiArtistTrack = $lastfm->getTrack($artistName);
			$hasApiArtistTrack = (isset($apiArtistTrack["toptracks"]["track"])) ? true : false ;

			if( $hasApiArtistTrack ){
				$arrApiArtistTrack = $apiArtistTrack["toptracks"]["track"];

                $artist = Artist::where('name',$artistName)->first();
                if( $artist != NULL ){
                    Track::where('artist_id',$artist->id)->delete();
                }

                foreach($arrApiArtistTrack as $apiData){
                    $track = new Track();
                    $track->artist_id  = $artist->id;
                    $track->name       = $apiData["name"];
                    $track->play_count = $apiData["playcount"];
                    $track->save();
				}
			}
		}
	}
}