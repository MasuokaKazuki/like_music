import React, { useState, useEffect } from 'react';
import {render} from 'react-dom';
import { BrowserRouter, Route } from 'react-router-dom';
import axios from "axios";
import YouTube from 'react-youtube';
import '../scss/main.scss';

const ListTitle = (props) => {
    return (
      <h1 className="list-title">{props.value}に近いアーティストのプレイリスト</h1>
    );
}

const Track = (props) => {
    const currentClass = ( props.current == true ) ? ' track--current' : '' ;
    return (
        <div className={"track" + currentClass}> 
            <div className="track__image track__image--circled">
                <img src={props.image} width="85" alt={props.name} />
            </div>
            <div className="track__name">{props.name}</div>
            <div className="track__artist">{props.artist}</div>
        </div>
    );
}

const SearchResult = (props) =>{
    const[searchArtist, setSearchArtist] = useState();
    const[trackList   , setTrackList   ] = useState([]);
    const[isLoding    , setIsLoding    ] = useState(false);
    const[currentTrack, setCurrentTrack] = useState({ index: 0, videoId: "" });

    useEffect(() => {
        const state = props.location.state;
        if(state.trackList){
            setTrackList(state.trackList);
            setSearchArtist(state.artistName);
            setCurrentTrack({
                index  : 0,
                videoId: state.trackList[0].video_id
            });
            setIsLoding(true);
        }
    }, []);

    useEffect(() => {
        const elem = document.getElementsByClassName('track--current');
        if(elem[0]){
            setTimeout(function(){
                const rect = elem[0].getBoundingClientRect();
                const elemtop = rect.top;
                    document.documentElement.scrollTop = elemtop - window.innerHeight/2*0.9;
            }, 10);
        }

    }, [currentTrack]);

    const play = (e) =>{
        setCurrentTrack({
            index:   e.currentTarget.getAttribute("data-index"),
            videoId: e.currentTarget.getAttribute("data-videoid")
        });
    }

    const opts = {
        height: '560',
        width: '560',
        playerVars: {
          autoplay: 1,
          modestbranding: 1,
        }
    }

    const onPlayerStateChange = (e) => {
        switch(e.data){
            case YT.PlayerState.ENDED:
                const nextIndex  = Number(currentTrack.index) + 1;
                const nextTarget = document.querySelector('[data-index="' + nextIndex + '"]');
                if(nextTarget){
                    setCurrentTrack({
                        index  : nextIndex,
                        videoId: nextTarget.getAttribute("data-videoid")
                    });
                }
                break;

            case YT.PlayerState.PLAYING:
            case YT.PlayerState.PAUSED:
            case YT.PlayerState.BUFFERING:
            case YT.PlayerState.CUED:
                break;
        }
    }

    const onPlayerError = (e) => {
        console.log("error");
    }

    const pageBack = (e) =>  {
        props.history.push('/');
    }

    if (isLoding && trackList[0]) {
        return (
            <div className="search-result-page">
                <div className="video-area">
                    <YouTube videoId={currentTrack.videoId} opts={opts} onStateChange={onPlayerStateChange} onError={onPlayerError}/>
                </div>

                <ListTitle value={searchArtist}/>

                <section className="playlist">
                    {trackList.map((item,i) => (
                        <a href="#" key={i} data-index={i} data-videoid={item.video_id} onClick={play}>
                            <Track name={item.track} artist={item.artist} image={item.video_thumbnail} current={i == currentTrack.index ? true : false} />
                        </a>
                    ))}
                </section>

                <div className="search-button" onClick={pageBack}>
                    <i className="fa fa-search fa-lg" style={{color:'#fff'}}></i>
                </div>
            </div>
        );
    }else{
        return(
            <div>loading...</div>
        );
    }
}

const TopPage = (props) => {
    const[isError, setIsError] = useState(false);
    const[isLoding   , setIsLoding   ] = useState(false);
    const[placeholder, setPlaceholder] = useState("好きなアーティスト名を入力して探そう");

    const errorClass = ( isError == true ) ? ' search__input--error' : '' ;

    useEffect(() => {
        if(sessionStorage.getItem('artist')){
            const artist = document.querySelector('input[name="artist"]');
            artist.value = sessionStorage.getItem('artist');
        }
    }, []);

    const searchAction = (e) =>  {
        const artist = document.querySelector('input[name="artist"]');
        if(artist.value){
            sessionStorage.artist = artist.value;
            setIsError(false);
            setIsLoding(true);
            getApiData(artist.value);
        }else{
            setIsError(true);
            setPlaceholder("アーティスト名を入力して下さい。");
        }
    }
    
    const getApiData = (artistName) => {
        axios.get('http://192.168.33.10/api/v1/artist/' + artistName + '/similarTrack')
            .then(
                (result) => {
                    props.history.push({
                        pathname: '/search',
                        state: { 
                            trackList: result.data.similar_artist_track,
                            artistName: artistName,
                        }
                    });
                }
            )
            .catch(
                (error) => {
                    getLastfmApiData(artistName);
                    console.log(error);
                }
            )
            .finally(() => {
                updateArtistdata(artistName);
            });
    }

    const updateArtistdata = (artistName) => {
        axios.get('http://192.168.33.10/api/v1/artist/' + artistName)
            .catch(
                (error) => {
                    console.log(error);
                }
            );
    }

    const getLastfmApiData = (artistName) =>{
        axios.get('http://192.168.33.10/api/v1/lastfm/' + artistName + '/similarTrack')
            .then(
                (result) => {
                    getYoutubeApiData(result.data.similar_artist_track,artistName);
                }
            )
            .catch(
                (error) => {
                    setIsLoding(false);
                    alert("お探しのアーティストに関する情報は見つかりませんでした。");
                    console.log(error);
                }
            );
    }

    const getYoutubeApiData = async (trackList,artistName) =>{
        let cnt = 0;
        let tracksData = [];
        for (let i = 0; i < trackList.length; ++i) {
            if(trackList[i].artist && trackList[i].track){
                await axios.get('http://192.168.33.10/api/v1/youtube/' + trackList[i].artist + '/' + trackList[i].track )
                    .then(
                        (result) => {
                            if(result.data.video_id && result.data.video_thumbnail){
                                const tmp = {
                                    artist: trackList[i].artist,
                                    track: trackList[i].track,
                                    video_id: result.data.video_id,
                                    video_thumbnail: result.data.video_thumbnail,
                                }
                                tracksData.push(tmp);
                                cnt++;
                            }
                        }
                    )
                    .catch(
                        (error) => {
                            console.log(error);
                        }
                    );
                if(cnt >= 15) break;
            }
        }
        if(tracksData.length){
            props.history.push({
                pathname: '/search',
                state: { 
                    trackList: tracksData,
                    artistName: artistName,
                }
            });
        }else{
            setIsLoding(false);
        }
    }

    let button;
    let inputDisabled;
    if (isLoding) {
        button = <div className="spinner">
                    <div className="rect1"></div>
                    <div className="rect2"></div>
                    <div className="rect3"></div>
                    <div className="rect4"></div>
                    <div className="rect5"></div>
                </div>;
        inputDisabled = "disabled";

    } else {
        button = <button className="search__button" onClick={searchAction}>
                    見つける <i className="fa fa-search"></i>
                </button>;
        inputDisabled = "";
    }

    const onEntryKey = (e) =>{
        if (e.key == 'Enter') {
            searchAction();
        }
    }

    return (
        <div className="top-page">
            <div className="top-content">
                <div className="top-content__title">
                    <img src="http://192.168.33.10/common/image/logo.svg" alt="Like Music" />
                </div>
                <div className="top-content__catch">自分の好きなアーティストを入力するだけで、<br/>新しい「好き」を見つかる音楽アプリ。</div>

                <div className="search">
                    <input className={"search__input" + errorClass} type="text" name="artist" placeholder={placeholder} disabled={inputDisabled} onKeyPress={(e) => onEntryKey(e)}/>
                    {button}
                </div>
            </div>
        </div>
    );
}

const App = (props) => {
    return(
        <BrowserRouter>
            <Route exact={true} path='/' component={TopPage}/>
            <Route exact={true} path='/search' component={SearchResult}/>
        </BrowserRouter>
    );
}

render(<App/>, document.getElementById('app'));