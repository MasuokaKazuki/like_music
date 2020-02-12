<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;

class SimilarArtistTrackController extends Controller
{
    /**
     * アーティストの情報が存在しているか確認する。
     * 無い場合 or 3か月以上前の情報の場合には、Last.FM APIより情報を取得し更新する。
     */
    public function check($artistName=null){
        if($artistName!=null){
            $hasArtistData = Artist::where('name',$artistName)->whereDate('created_at', '>', date('Y-m-d', strtotime('-3 month')))->exists();
            if(!$hasArtistData){
                echo "データ更新をする";
                // データを更新すべき
                //last.fm APIから情報を取得する
                    //情報がある場合は、レコードへ登録する。
                        // アーティスト名で検索し、データを取得
                        // if(アーティスト情報あり){
                            // アーティストの関連トラックを削除
                            // アーティストの似たアーティストを削除
                            // アーティスト情報に関しては、特に中身は更新はしないが、updateだけ実行する
                        // else{
                            // アーティスト情報を登録する
                            // 登録完了したアーティスト情報を取得する
                        //}
                        // アーティストの関連トラックを追加
                        // アーティストの似たアーティストを追加

                        // try catchで例外をひっかける
            }
            print_r($hasArtistData);
        }
    }

    /**
     * アーティストの似たアーティストの曲を返却する。
     */
    public function index(){
        $tmp = date('Y-m-d', strtotime('-3 month'));
        $this->check("BUMP OF CHICKEN");
        //return $tmp;
    }
}
