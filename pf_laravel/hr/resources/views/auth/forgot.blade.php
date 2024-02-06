@extends('layout.site', ['title' => __('site.forgot')])

@section('content')
    <h1>@lang('site.forgot')</h1>
    <section class="personal">
    <form method="post" action="{{ route('forgot-mail') }}">
        @csrf
        <div class="personal__container">
            <input type="email" class="personal__input personal__input--first input" name="email" placeholder="@lang('site.address_email')"
                   required maxlength="255" value="{{ old('email') ?? '' }}">
        </div>
        <div class="form-group">
            <button type="submit" class="registration__btn forgot__btn button">@lang('site.restore')</button>
        </div>
    </form>
    </section>
@endsection
