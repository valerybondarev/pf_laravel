@extends('layout.site', ['title' => __('site.reset_password')])

@section('content')
    <h1>@lang('site.reset_password')</h1>
    <form method="post" action="{{ route('reset-password') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-group">
            <input type="text" class="form-control" name="password"
                   placeholder="@lang('site.new_password')" required maxlength="255" value="">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="password_confirmation"
                   placeholder="@lang('site.confirm_password')" required maxlength="255" value="">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-info text-white">@lang('site.reset_password')</button>
        </div>
    </form>
@endsection
