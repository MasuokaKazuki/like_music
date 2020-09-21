import React, { useState } from 'react';
import {render} from 'react-dom';
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
        <Track/>
        <div className="track track--current">
          <div className="track__image track__image--circled">
            <img src="" width="85" alt="アーティスト画像" />
          </div>
          <div className="track__name">ばらの花×ネイティブダンサー</div>
          <div className="track__artist">yui（FLOWER FLOWER）とミゾベリョウ（odol）</div>
        </div>
        <Track/>
        <Track/>
        <Track/>
        <Track/>
        <Track/>
        <Track/>
        <Track/>
        <Track/>
      </section>
    );
  }
}

class Track extends React.Component {
  render () {
    return (
      <div className="track">
        <div className="track__image track__image--circled">
          <img src="" width="85" alt="アーティスト画像" />
        </div>
        <div className="track__name">ばらの花×ネイティブダンサー</div>
        <div className="track__artist">yui（FLOWER FLOWER）とミゾベリョウ（odol）</div>
      </div>
    );
  }
}

class SearchButton extends React.Component {
  render () {
    return (
      <div className="search-button"><i className="fa fa-search fa-lg" style={{color:'#fff'}}></i></div>
    );
  }
}

const App = (props) => {
    return (
      <div className="search-result-page">
        <VideoArea value="F6_zbnfxoBA" />
        <ListTitle value="ASIAN KUNG-FU GENERATION"/>
        <PlayList/>
        <SearchButton/>
      </div>
    );
}

render(<App/>, document.getElementById('app'));