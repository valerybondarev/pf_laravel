@extends('layout.site', ['title' => __('site.signup')])

@section('content')
    <h1>@lang('site.signup')</h1>
    <form method="post" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <input type="text" class="form-control" name="name" placeholder="@lang('site.name_surname')"
                   required maxlength="255" value="{{ old('name') ?? '' }}">
        </div>
        <div class="form-group">
            <input type="email" class="form-control" name="email" placeholder="@lang('site.email')"
                   required maxlength="255" value="{{ old('email') ?? '' }}">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="password" placeholder="@lang('site.new_password')"
                   required maxlength="255" value="">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="password_confirmation"
                   placeholder="@lang('site.confirm_password')" required maxlength="255" value="">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-info text-white">@lang('site.signup')</button>
        </div>
    </form>
@endsection
