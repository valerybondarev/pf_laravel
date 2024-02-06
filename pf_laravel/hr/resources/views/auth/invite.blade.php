@extends('layout.site', ['title' => __('site.invite_user')])

@section('content')

    <section class="candidates">
        <h2 class="candidates__heading heading">@lang('site.invite_user')</h2>
    </section>

    <section class="addition">
        <form method="post" action="{{ route('invite-mail') }}">
            @csrf
            <label class="addition__label label" for="name">@lang('site.new_user')</label>
            <input class="addition__input input" type="email" id="email" name="email"
                   placeholder="@lang('site.address_email')" value="{{ old('email') ?? '' }}" required />
            <button type="submit"  class="addition__btn button">@lang('site.add')</button>
        </form>
    </section>

@endsection
