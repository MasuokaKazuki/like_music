<?php
namespace App\Libraries;

class GoogleCustomSearch{
	private $apiBaseUrl = "https://www.googleapis.com/customsearch/v1/siterestrict";

	/**
	 * Google Custom Search APIからアーティスト+トラック名検索した結果合致したもののURLを返却する。
	 */
	public function getTrackMovie($artistName="",$trackName=""){
		if( $artistName=="" || $trackName=="") return NULL;

		$searchResult = $this->getTrackSearchResult($artistName,$trackName);

		if(isset($searchResult['items'])){
			foreach($searchResult['items'] as $item){
				$title = $item['title'];
				if( ( stristr($title, $artistName) != FALSE ) && 
					( stristr($title, $trackName ) != FALSE ) &&
					( stristr($item['link'], "https://www.youtube.com/watch?v=" ) != FALSE ) ){
				}
				return $item['link'];
			}
		}

		return NULL;
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