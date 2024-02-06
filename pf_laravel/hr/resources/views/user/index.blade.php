@extends('layout.site', ['title' => __('site.admin_panel')])

@section('content')
    <h1>@lang('site.admin_panel')</h1>
    <p>{{ __('site.admin_panel_weelcom', ['name' => auth()->user()->name]) }}</p>
    <p>@lang('site.admin_panel_text') @lang('site.admin_panel_link')
        <a href="{{ route('user.edit', auth()->user()->id) }}">
            @lang('site.admin_panel_profile')
        </a>
    </p>
@endsection
