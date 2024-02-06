<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? env('APP_NAME') }}</title>

    <link rel="preload" href="/fonts/ubunturegular.woff2" as="font" crossorigin>
    <link rel="preload" href="/fonts/ubunturegular.woff" as="font" crossorigin>
    <link rel="preload" href="/fonts/ubuntumedium.woff2" as="font" crossorigin>
    <link rel="preload" href="/fonts/ubuntumedium.woff" as="font" crossorigin>
    <link rel="preload" href="/fonts/ubuntubold.woff2" as="font" crossorigin>
    <link rel="preload" href="/fonts/ubuntubold.woff" as="font" crossorigin>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>

<body class="page-body">

    <header class="page-header">
        <a class="page-header__logo">
            <picture>
                <img class="page-header__logo-image" src="/img/logo.png" srcset="/img/logo.png 2x" width="165" height="44"
                     alt="@lang('site.logo')">
            </picture>
        </a>


        <nav class="main-nav">

            @php $locale = session()->get('locale'); @endphp
            <div class="langs">
                <div class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        @switch($locale)
                            @case('ru')
                            <img src="{{asset('img/flags/ru.png')}}" width="25px">
                            @break
                            @case('en')
                            <img src="{{asset('img/flags/en.png')}}" width="25px">
                            @break
                            @default
                            <img src="{{asset('img/flags/ru.png')}}" width="25px">
                        @endswitch
                        <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/lang/ru"><img src="{{asset('img/flags/ru.png')}}" width="25px"></a>
                        <a class="dropdown-item" href="/lang/en"><img src="{{asset('img/flags/en.png')}}" width="25px"></a>
                    </div>
                </div>
            </div>
            <ul class="main-nav__list site-list">
                @auth
                    @if(auth()->user()->role_id === 2 && in_array(request()->route()->getName(), ['welcome', 'user.edit']))
                        <li>
                            <a href="{{ route('user.edit', $user->id) }}" class="site-list__link {{ request()->is('user') ? 'site-list__link--active' : '' }}">
                                <svg class="site-list__svg" width="22" height="22">
                                    <use xlink:href="/img/sprite.svg#users">
                                    </use>
                                </svg>
                                @lang('site.profile')
                            </a>
                        </li>
                    @else
                        <li class="site-list__item">
                            <a href="{{ route('user.index') }}" class="site-list__link {{ request()->is('user') ? 'site-list__link--active' : '' }}">
                                <svg class="site-list__svg" width="22" height="22">
                                    <use xlink:href="/img/sprite.svg#users">
                                    </use>
                                </svg>
                                @lang('site.candidates')
                            </a>
                        </li>
                        <li class="site-list__item">
                            <a href="{{ route('invite') }}" class="site-list__link {{ request()->is('invite') ? 'site-list__link--active' : '' }}">
                                <svg class="site-list__svg" width="22" height="22">
                                    <use xlink:href="/img/sprite.svg#add">
                                    </use>
                                </svg>
                                @lang('site.invite_candidate')
                            </a>
                        </li>
                        <li class="site-list__item">
                            <a href="{{ route('guide') }}" class="site-list__link {{ request()->is('guide') ? 'site-list__link--active' : '' }}">
                                <svg class="site-list__svg" width="22" height="22">
                                    <use xlink:href="/img/sprite.svg#settings">
                                    </use>
                                </svg>
                                @lang('site.settings')
                            </a>
                        </li>
                    @endif
                @endif

                @guest
                    <li class="site-list__item">
                        <a href="{{ route('login') }}" class="site-list__link site-list__link--output">
                            <svg class="site-list__svg" width="22" height="22">
                                <use xlink:href="/img/sprite.svg#output">
                                </use>
                            </svg>
                            @lang('site.auth')
                        </a>
                    </li>
                @else
                    <li class="site-list__item">
                        <a href="{{ route('logout') }}" class="site-list__link site-list__link--output">
                            <svg class="site-list__svg" width="22" height="22">
                                <use xlink:href="/img/sprite.svg#output">
                                </use>
                            </svg>
                            @lang('site.out')
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
    </header>

    <main class="page-main">


            <div class="messages">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible mt-0" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ $message }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible mt-4" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>

    </main>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/site.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
