import React, { useState, useEffect, Suspense } from 'react';
import {render} from 'react-dom';
import axios from "axios";
import '../scss/main.scss';

const VideoArea = (props) => {
    return (
        <div className="video-area">
            <iframe width="560" height="315" src={"https://www.youtube.com/embed/" + props.value} frameBorder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowFullScreen></iframe>
        </div>
    );
};

const ListTitle = (props) => {
    return (
      <h1 className="list-title">{props.value}に近いアーティストのプレイリスト</h1>
    );
}

class PlayList extends React.Component {
    render () {
        return (
        <section className="playlist">
            <Track name="ばらの花×ネイティブダンサー" artist="yui（FLOWER FLOWER）とミゾベリョウ（odol）" image="" />
            <div className="track track--current">
            <div className="track__image track__image--circled">
                <img src="" width="85" alt="アーティスト画像" />
            </div>
            <div className="track__name">ばらの花×ネイティブダンサー</div>
            <div className="track__artist">yui（FLOWER FLOWER）とミゾベリョウ（odol）</div>
            </div>
            <Track name="ばらの花×ネイティブダンサー" artist="yui（FLOWER FLOWER）とミゾベリョウ（odol）" image="" />
            <Track name="ばらの花×ネイティブダンサー" artist="yui（FLOWER FLOWER）とミゾベリョウ（odol）" image="" />
            <Track name="ばらの花×ネイティブダンサー" artist="yui（FLOWER FLOWER）とミゾベリョウ（odol）" image="" />
            <Track name="ばらの花×ネイティブダンサー" artist="yui（FLOWER FLOWER）とミゾベリョウ（odol）" image="" />
            <Track name="ばらの花×ネイティブダンサー" artist="yui（FLOWER FLOWER）とミゾベリョウ（odol）" image="" />
            <Track name="ばらの花×ネイティブダンサー" artist="yui（FLOWER FLOWER）とミゾベリョウ（odol）" image="" />
            <Track name="ばらの花×ネイティブダンサー" artist="yui（FLOWER FLOWER）とミゾベリョウ（odol）" image="" />
            <Track name="ばらの花×ネイティブダンサー" artist="yui（FLOWER FLOWER）とミゾベリョウ（odol）" image="" />
        </section>
        );
    }
}

const Track = (props) => {
    return (
      <div className="track">
        <div className="track__image track__image--circled">
          <img src={props.image} width="85" alt={props.name} />
        </div>
        <div className="track__name">{props.name}</div>
        <div className="track__artist">{props.artist}</div>
      </div>
    );
}

const SearchButton = () => {
    return (
      <div className="search-button"><i className="fa fa-search fa-lg" style={{color:'#fff'}}></i></div>
    );
}

const SearchResult = () =>{
    useEffect(() => { 
        const params = new URLSearchParams(location.search);
        if(params && params.get("search")){
           getApiData(params.get("search"));
        }
    }, []);

    const getApiData = (artistName) => {
        axios.get('http://192.168.33.10/api/v1/artist/' + artistName + '/similarTrack')
            .then(
                (results) => {
                    console.log(result);
                }
            )
            .catch(
                (error) => {
                    console.log(error);
                }
            );
    }

    return (
        <div className="search-result-page">
            <VideoArea value="F6_zbnfxoBA" />
            <ListTitle value="ASIAN KUNG-FU GENERATION"/>
            <PlayList/>
            <SearchButton/>
        </div>
    );
}

const App = (props) => {
    return (
        <Suspense fallback={<p>Loading...</p>}>
            <SearchResult/>
        </Suspense>
    );
}

render(<App/>, document.getElementById('app'));