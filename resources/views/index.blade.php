<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
        <title>Like Music</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">    <!-- Fonts -->
        <link rel="stylesheet" href="{{ asset('/common/css/main.css') }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
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
                    <button type="submit" class="search__button">
                        見つける <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </body>
</html>
