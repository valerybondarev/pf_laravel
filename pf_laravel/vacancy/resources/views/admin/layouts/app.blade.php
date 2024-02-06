@extends('admin.layouts.base')

@section('body')
    @include('admin.layouts.blocks.sidebar')
    <div class="main-content" id="panel">
        <div class="{{ config('admin.background') }} pb-8">
            @include('admin.layouts.blocks.nav')
            @include('admin.layouts.blocks.header')
        </div>
        <div class="container-fluid mt--7" id="app">
            @yield('content')
            @include('admin.layouts.blocks.footer')
        </div>
    </div>
@endsection

