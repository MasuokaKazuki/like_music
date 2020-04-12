<?php
namespace App\Libraries;

class Lastfm{
	private $responseFormat = 'json';
	private $apiBaseUrl = "http://ws.audioscrobbler.com/2.0/";

	/**
	 * Last.fm APIからアーティスト情報を取得する。
	 */
	public function getArtist($artistName=""){
		if( $artistName=="" ) return NULL;

		$urlQuery['method'] = 'artist.getinfo';
		$urlQuery['artist'] = $artistName;
		$url = $this->createApiUrl($urlQuery);
		
		return $this->getApiToArray($url);
	}
	
	/**
	 * Last.fm APIから似たアーティストを取得する。
	 */
	public function getArtistSimilar($artistName="",$limit=15){
		if( $artistName=="" ) return NULL;

		$urlQuery['method'] = 'artist.getsimilar';
		$urlQuery['artist'] = $artistName;
		$urlQuery['limit' ] = $limit;
		$url = $this->createApiUrl($urlQuery);

		return $this->getApiToArray($url);
	}
		
	/**
	 * Last.fm APIからトップトラックを取得する。
	 */
	public function getTrack($artistName="",$limit=5){
		if( $artistName=="" ) return NULL;

		$urlQuery['method'] = 'artist.gettoptracks';
		$urlQuery['artist'] = $artistName;
		$urlQuery['limit' ] = $limit;
		$url = $this->createApiUrl($urlQuery);

		return $this->getApiToArray($url);
	}
	
	/**
	 * Last.fm APIのURLを生成
	 */
	private function createApiUrl($urlQuery=array()){
		$urlQuery['api_key'] = $this->getKey();
		$urlQuery['format' ] = $this->responseFormat;

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
	 *  似たアーティストの曲情報をAPIから取得し結合して、返却する
	 */
	public function getSimilarArtistTrack($artistName="",$artistLimit=15,$trackLimit=5){
		$time_start = microtime(true);
		
		$similarArtistTracks = array();
		$apiSimilarArtist = $this->getArtistSimilar($artistName,$artistLimit);	// 似たアーティスト取得する

		$time = microtime(true) - $time_start;
		//echo "<p>似たアーティストの取得{$time} 秒</p>";

		if(isset($apiSimilarArtist["similarartists"]["artist"]) && is_array($apiSimilarArtist["similarartists"]["artist"])){
			$arrSimilarArtist = $apiSimilarArtist["similarartists"]["artist"];

			foreach($arrSimilarArtist as $similarArtist){
				$artistName  = $similarArtist["name"];
				$artistTrack = $this->getTrack($artistName,$trackLimit);
				
				if(isset($artistTrack["toptracks"]["track"]) && is_array($artistTrack["toptracks"]["track"])){
					$arrTrack = $artistTrack["toptracks"]["track"];
					foreach($arrTrack as $track){
						$similarArtistTracks[] = array('artist'=>$artistName, 'track'=>$track["name"]);
					}
				}
			}
		}
		$time = microtime(true) - $time_start;
		//echo "<p>トラックの取得と結合の取得{$time} 秒</p>";

		if(is_array($similarArtistTracks)) shuffle($similarArtistTracks);
		return $similarArtistTracks;
	}

	/**
	 * Last.fm API KEYの取得
	 */
	public function getKey(){
		return config('lastfm.key');
	}
}