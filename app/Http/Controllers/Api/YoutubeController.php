<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Libraries\GoogleCustomSearch;
use Illuminate\Support\Facades\Request;

class YoutubeController extends Controller
{
	/**
	 * youtubeの情報を取得し返却する。
	 */
	public function index($artistName,$trackName){
		$cse = new GoogleCustomSearch();
		$tmp = $cse->getTrackMovieData($artistName,$trackName);
		$videoId   = $tmp['id'];
		$thumbnail = $tmp['thumbnail'];
		return response()->json(['video_id' => $videoId, 'video_thumbnail' => $thumbnail]);
	}
}
