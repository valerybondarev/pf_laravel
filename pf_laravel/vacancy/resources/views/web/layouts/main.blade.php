<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{--<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=no">--}}
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    @if(isset($tourClub))
        <meta name="og:title" content="{{ $tourClub->title }}">
        <meta name="og:description" content="Информация о ближайших мероприятиях и о том как вступить в турклуб">
        <meta name="og:image" content="https://sun3.userapi.com/sun3-9/s/v1/if2/H1o-F0N8iGff1BkK7RlsolRj-47Bi6buoNMMNtfb2twKhwstlc_9LA1jOsy1ryTywKeh3NaPzYAANYEqS87IAmCi.jpg?size=100x100&quality=96&crop=93,20,1116,1116&ava=1">
    @endif

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ mix('web/css/all.css') }}">
    <title>{{ config('app.name') }}</title>
    @if(isset($title))
        @section('title', $title . ' - ' . config('app.name'))
    @else
        @section('title', config('app.name'))
    @endif
</head>
<body class="font-sans antialiased">
    <main>
        @yield('content')
    </main>
</body>
</html>
