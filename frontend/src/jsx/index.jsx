import React, { useState, useEffect } from 'react';
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
                    setIsLoding(true);
                    setCurrentTrack({
                        index  : 0,
                        videoId: result.data.similar_artist_track[0].video_id
                    });
                }
            )
            .catch(
                (error) => {
                    setSearchArtist('');
                    console.log(error);
                }
            );
    }

    if (isLoding && trackList[0]) {
        return (
            <div className="search-result-page">
                <VideoArea value={currentTrack.videoId} />
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
            <p>now loading...</p>
        );
    }
}

const App = (props) => {
    return (
        <SearchResult/>
    );
}

render(<App/>, document.getElementById('app'));