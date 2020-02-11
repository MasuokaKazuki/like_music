<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SimilarArtistTrackController extends Controller
{
    /**
     * アーティストの情報が存在しているか確認する。
     * 無い場合 or 3か月以上前の情報の場合には、Last.FM APIより情報を取得し更新する。
     */
    public function check(){

    }

    /**
     * アーティストの似たアーティストの曲を返却する。
     */
    public function index(){
        return "test";
    }
}
