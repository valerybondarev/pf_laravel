<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('admin.title') }}</title>

    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/png">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

    <!-- Icons -->
    <link href="{{ asset('admin/vendor/nucleo/css/nucleo.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

    <!-- Page plugins -->
    <link href="{{ asset('admin/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/animate.css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">

    <!-- Argon CSS -->
    <link type="text/css" href="{{ asset('admin/css/all.css') }}" rel="stylesheet">

    @stack('css')
</head>
<body @isset($class) class="{{ $class }}" @endisset>

<div class="app__container">

    @include('admin.layouts.blocks.preloader')

    <div class="app__content">
        @yield('body')
    </div>
</div>

<!-- Core -->
<script src="{{ asset('admin/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('admin/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/vendor/js-cookie/js.cookie.js') }}"></script>
<script src="{{ asset('admin/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('admin/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
<script src="{{ asset('admin/vendor//bootstrap-notify/bootstrap-notify.min.js') }}"></script>

<!-- Optional JS -->
<script src="{{ asset('admin/vendor/moment/min/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('admin/vendor/moment/locale/ru.js') }}"></script>
<script src="{{ asset('admin/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('admin/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('admin/vendor/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('admin/vendor/select2/dist/js/i18n/'.app()->getLocale().'.js') }}"></script>
@stack('js')


<!-- Argon JS -->
<script src="{{ asset('admin/js/all.js') }}"></script>


</body>
</html>
