<?php
namespace App\Libraries;

class YouTube{
	private $apiBaseUrl = "https://www.googleapis.com/youtube/v3/search";

	/**
	 * YouTube APIからアーティスト+トラック名で検索した結果を取得する。
	 */
	public function getTrackMovie($artistName="",$trackName=""){
		if( $artistName=="" || $trackName="") return NULL;

		$urlQuery['type'] = "video";
		$urlQuery['part'] = "snippet";
		$urlQuery['q'   ] = $artistName." ".$trackName;
		$urlQuery['maxResults'] = 5;
		$url = $this->createApiUrl($urlQuery);
		
		return $this->getApiToArray($url);
	}
	
	/**
	 * YouTube APIのURLを生成
	 */
	private function createApiUrl($urlQuery=array()){
		$urlQuery['api_key'] = $this->getKey();

		return $this->apiBaseUrl."?".http_build_query($urlQuery);
	}

	/**
	 * APIからデータを取得して、配列に変換して返却する
	 */
	private function getApiToArray($url=""){
		$result = "";
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