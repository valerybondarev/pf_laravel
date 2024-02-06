<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? env('APP_NAME') }}</title>
    <link href="{{ asset('css/normalize.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>

<body class="front-page">
<header class="header">
    <div class="container z-index">
        <div class="header__top">
            <a href="#" class="header__logo"><img src="{{ asset('img/logo.png') }}" alt=""></a>
            <ul class="header__menu mobile-none">
                @auth()
                    <li><a href="/admin">Админпанель</a></li>
                @endauth

                <li><a href="#">@lang('site.about') hr.webdoka</a></li>
                <li><a href="#">@lang('site.how_to_use')</a></li>
                <li><a href="#">@lang('site.contacts')</a></li>
            </ul>
            <a href="{{ route('candidate') }}" class="header__use mobile-none">@lang('site.try')</a>
            <span class="header__burger mobile-only"></span>
        </div>
        <ul class="header__soc mobile-none">
            <li><a href="#"><img src="{{ asset('img/whats.svg') }}" alt=""></a></li>
            <li><a href="#"><img src="{{ asset('img/viber.svg') }}" alt=""></a></li>
            <li><a href="#"><img src="{{ asset('img/telegram.svg') }}" alt=""></a></li>
        </ul>
        <div class="header__center">
            <div class="header__center__box">
                <p class="header__dec1">база данных для рекрутмента</p>
                <h1 class="header__title">Сделай проще свою работу с персоналом</h1>
                <p class="header__dec2">Предлагаем бесплатную систему для хранения информации о каждом кандидате.
                </p>
                <a href="{{ route('candidate') }}" class="header__use2 btn">@lang('site.try')</a>
            </div>
        </div>
        <div class="header__image">
            <img src="{{ asset('img/header__image.png') }}" alt="" class="header__pic">
        </div>
    </div>
    <span class="header__border"></span>
</header>
<section class="main">
    <div class="container">
        <div class="main__box">
            <div class="main__img">
                <img src=" {{ asset('img/main__image.png') }}" alt="">
            </div>
            <div class="main__text">
                <p class="main__dec">Hr.webdoka</p>
                <h2 class="main__title">База данных для кандидатов и действующих сотрудников</h2>
                <ol class="main__ol">
                    <li>Бесплатная база для хранения информации </li>
                    <li>Полная анкета кандидата (фото, резюме с hh.ru и другое) </li>
                    <li>Возможность выгружать в систему необходимые документы</li>
                    <li>Функция отслеживания занятости для временного персонала</li>
                    <li>Простой и удобный интерфейс</li>
                </ol>
                <a href="{{ route('candidate') }}" class="btn">@lang('site.try')</a>
            </div>
        </div>
    </div>
</section>
<footer class="footer">
    <div class="container">
        <div class="footer__box">
            <div class="footer__copirit">Webdoka © 2004-2022</div>
            <div class="footer__phone-email">
                <div class="footer__contact"><span>@lang('site.phone')</span> (Спб) {{ Config::get('app.phone') }}</div>
                <div class="footer__contact"><span>E-mail</span> {{ Config::get('app.email') }}</div>
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

<script src="{{ asset('js/main.js') }}"></script>
</body>

</html>
