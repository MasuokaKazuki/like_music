import React, { useState, useEffect } from 'react';
import {render} from 'react-dom';
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

const SearchButton = () => {
    return (
      <div className="search-button"><i className="fa fa-search fa-lg" style={{color:'#fff'}}></i></div>
    );
}

const TopPage = (prop) => {
    return (
        <div className="top-page">
            <div className="top-content">
                <div className="top-content__title">
                    <img src="http://192.168.33.10/common/image/logo.svg" alt="Like Music" />
                </div>
                <div className="top-content__catch">キャッチコピーキャッチコピーキャッチコピーキャッチコピー</div>

                <div className="search">
                    <form action="#" method="get">
                        <input className="search__input" type="text" name="search" value={prop.artist} placeholder="好きなアーティスト名を入力して探そう" />
                        <button type="submit" className="search__button">
                            見つける <i className="fa fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    );
}

const SearchResult = () =>{
    const[searchArtist, setSearchArtist] = useState();
    const[trackList   , setTrackList   ] = useState([]);
    const[isLoding    , setIsLoding    ] = useState(false);
    const[currentTrack, setCurrentTrack] = useState({ index: 0, videoId: "" });

    useEffect(() => { 
        const params = new URLSearchParams(location.search);
        if(params && params.get("search")){
            getApiData(params.get("search"));
            setSearchArtist(params.get("search"));
        }
    }, []);

    const play = (e) =>{
        setCurrentTrack({
            index:   e.currentTarget.getAttribute("data-index"),
            videoId: e.currentTarget.getAttribute("data-videoid")
        });
    }

    const getApiData = (artistName) => {
        axios.get('http://192.168.33.10/api/v1/artist/' + artistName + '/similarTrack')
            .then(
                (result) => {
                    setTrackList(result.data.similar_artist_track);
                    setCurrentTrack({
                        index  : 0,
                        videoId: result.data.similar_artist_track[0].video_id
                    });

                    setIsLoding(true);
                }
            )
            .catch(
                (error) => {
                    setSearchArtist('');
                    console.log(error);
                }
            );
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

    if (isLoding && trackList[0]) {
        return (
            <div className="search-result-page">
                <div className="video-area">
                    <YouTube videoId={currentTrack.videoId} opts={opts} onStateChange={onPlayerStateChange} />
                </div>

                <ListTitle value={searchArtist}/>

                <section className="playlist">
                    {trackList.map((item,i) => (
                        <a href="#" key={i} data-index={i} data-videoid={item.video_id} onClick={play}>
                            <Track name={item.track} artist={item.artist} image={item.video_thumbnail} current={i == currentTrack.index ? true : false} />
                        </a>
                    ))}
                </section>

                <SearchButton/>
            </div>
        );
    }else{
        return(
            <TopPage artist={searchArtist}/>
        );
    }
}

const App = (props) => {
    return (
        <SearchResult/>
    );
}

render(<App/>, document.getElementById('app'));