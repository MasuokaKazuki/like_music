<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Like Music</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">    <!-- Fonts -->
        <link rel="stylesheet" href="{{ asset('/common/css/main.css') }}">
    </head>
    <body class="top-page">
        <div class="top-content">
            <div class="top-content__title">
                <img src="{{ asset('/common/image/logo.svg') }}" alt="Like Music" />
            </div>
            <div class="top-content__catch">キャッチコピーキャッチコピーキャッチコピーキャッチコピー</div>

            <div class="search">
                <form action="#" method="get">
                    <input class="search__input" type="text" name="artist" placeholder="好きなアーティスト名を入力して探そう" />
                    <input class="search__button" type="submit" value="見つける" />
                </form>
            </div>
        </div>
        <!--<img src="{{ asset('/common/image/top-mainvisual.jpg') }}" />-->
    </body>
</html>
