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

const PlayList = (props) => {
    if (props.list.map) {
        return (
            <section className="playlist">
                {props.list.map((item,i) => (
                    <Track key={i} name={item.track} artist={item.artist} image={item.video_thumbnail} current={i == 0 ? true : false} />
                ))}
            </section>
        );
    }else{
        return(<p>Loading...</p>);
    }
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
    const[data, setData] = useState([]);

    useEffect(() => { 
        const params = new URLSearchParams(location.search);
        if(params && params.get("search")){
            getApiData(params.get("search"));
            setSearchArtist(params.get("search"));
        }
    }, []);

    const getApiData = (artistName) => {
        axios.get('http://192.168.33.10/api/v1/artist/' + artistName + '/similarTrack')
            .then(
                (result) => {
                    setData(result.data.similar_artist_track);
                }
            )
            .catch(
                (error) => {
                    setSearchArtist('');
                    console.log(error);
                }
            );
    }

    return (
        <Suspense fallback={<p>Loading...</p>}>
            <div className="search-result-page">
                <VideoArea value="F6_zbnfxoBA" />
                <ListTitle value={searchArtist}/>
                <PlayList list={data}/>
                <SearchButton/>
            </div>
        </Suspense>
    );
}

const App = (props) => {
    return (
        <SearchResult/>
    );
}

render(<App/>, document.getElementById('app'));