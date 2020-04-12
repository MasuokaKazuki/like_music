<?php
namespace App\Libraries;

class YouTube{
	private $apiBaseUrl = "https://www.googleapis.com/youtube/v3/search";

	/**
	 * YouTube APIからアーティスト+トラック名で検索した結果を取得する。
	 */
	public function getTrackMovieData($artistName="",$trackName=""){
		if( $artistName=="" && $trackName=="") return NULL;

		$urlQuery['type'] = "video";
		$urlQuery['part'] = "snippet";
		$urlQuery['q'   ] = $artistName." ".$trackName;
		$urlQuery['maxResults'] = 1;
		$url = $this->createApiUrl($urlQuery);
		return $this->getApiToArray($url);
	}

	/**
	 * YouTube APIからアーティスト+トラック名で検索し、動画のIDを返却する
	 * ただし条件として、タイトルにトラック名が入っているものに限る
	 */
	public function getTrackMovieId($artistName="",$trackName=""){
		if( $artistName=="" && $trackName=="") return NULL;
		$movieId = NULL;
		$movieData = $this->getTrackMovieData($artistName,$trackName);
		if(isset($movieData) && is_array($movieData)){
			if(isset($movieData['items'][0]['snippet']['title']) &&
			   stripos($movieData['items'][0]['snippet']['title'],$trackName) !== false){
				$movieId = $movieData['items'][0]['id']['videoId'];
			}
		}
		return $movieId;
	}

	
	/**
	 * YouTube APIのURLを生成
	 */
	private function createApiUrl($urlQuery=array()){
		$urlQuery['key'] = $this->getKey();

		return $this->apiBaseUrl."?".http_build_query($urlQuery);
	}

	/**
	 * APIからデータを取得して、配列に変換して返却する
	 */
	private function getApiToArray($url=""){
		$result = NULL;
		if( $url != "" ){
			$response = file_get_contents($url);
			$result = json_decode($response,true);
		}
		return $result;
	}

	/**
	 * YouTube API KEYの取得
	 */
	public function getKey(){
		return config('youtube.key');
	}
}