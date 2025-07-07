<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MaviApostes - @yield('title')</title>
        <link href="../public/icon.png" rel="icon" type="image/png">
        <link rel="stylesheet" type="text/css" href="../resources/css/app.css?v=''">
    </head>
    <header>
        <h2>@yield('subtitle')</h2>
    </header>
    <body>
        <section id="row">
            <nav>
            @yield('navegador')
            @if(Session::has("logerr"))
                <div id="error">{{Session::get('logerr')}}</div>
            @endif

            </nav>
            <article>
                @if(Session::has("valid"))
                    <center><div style="color:white; font-size:25px; margin:10px;">{{Session::get('valid')}}</div></center>
                @endif
                @yield('information')
            </article>
        </section>
    </body>
</html>
