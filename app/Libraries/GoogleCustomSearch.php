<?php
namespace App\Libraries;

class GoogleCustomSearch{
	private $apiBaseUrl = "https://www.googleapis.com/customsearch/v1/siterestrict";

	/**
	 * Google Custom Search APIからアーティスト+トラック名検索した結果合致したもののVideoIDとサムネイル画像を返却する。
	 */
	public function getTrackMovieData($artistName="",$trackName=""){
		if( $artistName=="" || $trackName=="") return NULL;

		$trackMovieData = array();
		$searchResult = $this->getTrackSearchResult($artistName,$trackName);

		if(isset($searchResult['items'])){
			foreach($searchResult['items'] as $item){
				$title = $item['title'];
				$url   = $item['link'];
				if( ( stristr($title, $artistName) != FALSE ) && 
					( stristr($title, $trackName ) != FALSE ) &&
					( stristr($url  , "https://www.youtube.com/watch?v=" ) != FALSE ) ){
				}
				$trackMovieData['id'] = $this->extractVideoId($url);
				$trackMovieData['thumbnail'] = "";
			}
		}

		return $trackMovieData;
	}

	/**
	 * youtubeのURLからvideoIDを抽出する
	 */
	private function extractVideoId($url=""){
		$videoId = "";
		$tmp = str_replace('https://www.youtube.com/watch?v=', '', $url);
		$tmp = explode( "&", $tmp );
		if( isset($tmp[0]) ) $videoId = $tmp[0];
		return $videoId;
	}

	/**
	 * Google Custom Search APIからアーティスト+トラック名検索した結果合致したもののVideoIDとサムネイル画像を返却する。
	 */
	public function getTrackMovieId($artistName="",$trackName=""){
		$videoId = "";
		$trackMovieData = $this->getTrackMovieData($artistName,$trackName);
		if( isset($trackMovieData['id']) ){
			$videoId = $trackMovieData['id'];
		}
		return $videoId;
	}

	/**
	 * Google Custom Search APIからアーティスト+トラック名で検索した結果を取得する。
	 */
	public function getTrackSearchResult($artistName="",$trackName=""){
		if( $artistName=="" || $trackName=="") return NULL;

		$urlQuery['q'  ] = $artistName." ".$trackName;
		$urlQuery['num'] = 3;

		$url = $this->createApiUrl($urlQuery);
		
		return $this->getApiToArray($url);
	}
	
	/**
	 * Google Custom Search APIのURLを生成
	 */
	private function createApiUrl($urlQuery=array()){
		$urlQuery['key'] = $this->getKey();
		$urlQuery['cx' ] = $this->getCx();

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
	 * GoogleCustomSearchube API KEYの取得
	 */
	public function getKey(){
		return config('cse.key');
	}

	/**
	 * GoogleCustomSearchube 検索エンジンIDの取得
	 */
	public function getCx(){
		return config('cse.cx');
	}
}