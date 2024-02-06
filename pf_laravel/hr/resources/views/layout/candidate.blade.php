<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? env('APP_NAME') }}</title>
    <link href="{{ asset('css/normalize.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>
<header class="header-page">
    <div class="container">
        <a href="#" class="header-page__logo"></a>
        <div class="header-page__contact">
            @auth()
                <div class="header-page__phone mobile-none header-page__admin"><a href="/admin">Админпанель</a> </div>
            @endauth
            <div class="header-page__phone mobile-none"><span>@lang('site.phone')(Спб)</span> <a href="#">{{ Config::get('app.phone') }}</a> </div>
            <div class="header-page__email mobile-none"><span>E-mail</span> <a href="#">{{ Config::get('app.email') }}</a> </div>
        </div>
        <span class="header-page__burger header__burger mobile-only"></span>
    </div>
</header>
@yield('content')
<footer class="footer">
    <div class="container">
        <div class="footer__box">
            <div class="footer__copirit">Webdoka © 2004-2022</div>
            <div class="footer__phone-email">
                <div class="footer__contact"><span>@lang('site.phone')</span> (Спб) {{ Config::get('app.phone') }}</div>
                <div class="footer__contact"><span>E-mail</span> {{ Config::get('app.email') }} </div>
            </div>
        </div>
    </div>
</footer>
<div class="mobile-menu">
    <span class="mobile-menu__close"></span>
    <ul class="mobile-menu__list">
        <li><a href="#">@lang('site.about') hr.webdoka</a></li>
        <li><a href="#">@lang('site.how_to_use')</a></li>
        <li><a href="#">@lang('site.contacts')</a></li>
    </ul>
</div>

<script src="js/main.js"></script>
</body>

</html>
