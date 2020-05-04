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
		$tmp = $cse->getTrackMovieUrl($artistName,$trackName);
		return response()->json(['video_id' => $tmp]);
	}
}
