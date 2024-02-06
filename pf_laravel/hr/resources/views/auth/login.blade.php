@extends('layout.site', ['title' => __('site.login')])

@section('content')
    <div class="container">
    <h1>@lang('site.login')</h1>
        <section class="personal">
            <form method="post" action="{{ route('login') }}">
                @csrf
                <div class="personal__container">
                    <label class="personal__label label" for="email">@lang('site.email')</label>
                    <input type="email" class="personal__input personal__input--first input" id="email" name="email" placeholder="@lang('site.address_email')"
                           required maxlength="255" value="{{ old('email') ?? '' }}">
                    <label class="personal__label label" for="password">@lang('site.password')</label>
                    <input type="password" class="personal__input personal__input--first input" id="password" name="password" placeholder="@lang('site.you_password')"
                           required maxlength="255" value="">
                </div>
                <div class="form-group">
                    <a href="/forgot-password">@lang('site.forgot_password')</a>
                </div>
                <div class="form-group">
                    <button type="submit" class="registration__btn button">@lang('site.auth')</button>
                </div>
            </form>
        </section>
    </div>
@endsection
