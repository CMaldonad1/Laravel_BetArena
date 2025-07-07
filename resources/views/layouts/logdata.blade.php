
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MaviApostes - @yield('title')</title>
        <link href="../public/icon.png" rel="icon" type="image/png">
        <link rel="stylesheet" type="text/css" href="../resources/app/app.css?v='">
    </head>
    <header>
        <h2>@yield('subtitle')</h2>
    </header>
    <body>
    @if(session()->has('user'))
        <div id="logout"><button onclick="window.location.href='{{route('/log/logout')}}'">LogOut</button></div>
    @endif
    <div><button onclick="window.location.href='{{route('log')}}'">Torna</button></div>

        <article>
            @yield('information')
        </article>
    </body>
</html>
