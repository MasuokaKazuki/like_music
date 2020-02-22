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
	public function getArtistSimilar($artistName=""){
		if( $artistName=="" ) return NULL;

		$urlQuery['method'] = 'artist.getsimilar';
		$urlQuery['artist'] = $artistName;
		$urlQuery['limit' ] = 10;
		$url = $this->createApiUrl($urlQuery);

		return $this->getApiToArray($url);
	}
		
	/**
	 * Last.fm APIからトップトラックを取得する。
	 */
	public function getTrack($artistName=""){
		if( $artistName=="" ) return NULL;

		$urlQuery['method'] = 'artist.gettoptracks';
		$urlQuery['artist'] = $artistName;
		$urlQuery['limit' ] = 10;
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
		$result = "";
		if( $url != "" ){
			$response = file_get_contents($url);
			$result = json_decode($response,true);
		}
		return $result;
	}

	/**
	 * Last.fm API KEYの取得
	 */
	public function getKey(){
		return config('lastfm.key');
	}
}