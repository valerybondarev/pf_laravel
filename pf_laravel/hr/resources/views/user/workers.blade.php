@extends('layout.site', ['title' => __('site.candidates')])

@section('content')

    @php
        $confirm = __('site.confirm');
        $lang = session()->get('locale');
        $prefix = ($lang == 'ru') ? '' : '_' . $lang;
    @endphp

    <section class="candidates">
        <h2 class="candidates__heading heading">@lang('site.candidates')</h2>
        <a class="candidates__link button" href="{{ route('user.create') }}">@lang('site.new_user')</a>
    </section>

    <section class="tabs">
        <ul class="tabs__list filter__candidate">
            <li class="tabs__item">
                <button class="tabs__btn tabs__btn--active" data-tabs-path="filter">@lang('site.filter')</button>
            </li>
            <!--li class="tabs__item">
                <button class="tabs__btn" data-tabs-path="new-filter">Новый фильтр</button>
            </li-->
        </ul>
    </section>



    <div class="tabs__content tabs__content--active" data-tabs-target="filter">
        <div class="content">
            <section class="filter">
                <form id="form-user-filter" action="{{ route('user.index') }}" method="GET">
                    <div class="filter__container">

                        <label class="filter__label filter__label--first label" for="name">@lang('site.search')</label>
                        <label class="filter__label filter__label--second label" for="status">@lang('site.status')</label>
                        <label class="filter__label filter__label--third label" for="skill">@lang('site.skills')</label>
                        <input class="filter__input filter__input--first input" type="text" id="name" name="name"
                               placeholder="@lang('site.search_placeholder')"  value="{{ $filter['name'] ?? '' }}">
                        <select class="filter__input filter__input--second input select2-single" name="status"
                                id="status">
                            <option value="0">@lang('site.all')</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" @if(isset($filter['status']) && $status->id == $filter['status']) selected @endif>{{ $status->name }}</option>
                            @endforeach
                        </select>
                        <select class="filter__input filter__input--third input select2-multiple" name="skills[]"
                                id="skill" multiple>
                            <option>@lang('site.choose_skills')</option>
                            @foreach($skills as $skill)
                                <option value="{{ $skill->id }}" @if(isset($filter['skills']) && !empty($filter['skills']) && in_array($skill->id, $filter['skills'])) selected @endif>{{ $skill->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </section>

            <section class="selection">
                <div class="selection__container">
                    @if(!empty($workers))
                        <table class="selection__table">
                            <tbody>
                            <tr class="selection__block">

                                <td>
                                    <input class="radio__input" type="radio" name="target" id="number">
                                    <label class="radio__label" for="number">@lang('site.num')</label>
                                </td>

                                <td class="selection__text">@lang('site.name_surname')</td>

                                <td class="selection__text">@lang('site.email')</td>

                                <td class="selection__text">@lang('site.date_signup')</td>

                                <td class="selection__text">@lang('site.rights')</td>

                                <td class="selection__text">@lang('site.status')</td>

                                <td class="selection__none"></td>

                                <td class="selection__none"></td>

                                <td class="selection__none"></td>

                            </tr>

                            @foreach($workers as $key => $worker)

                                <tr>

                                    <td>
                                        <input class="radio__input" type="radio" name="target[{{ $worker->id }}]"
                                               id="one_{{ $worker->id }}">
                                        <label class="radio__label" for="one_{{ $worker->id }}">{{ ++$key }}</label>
                                    </td>

                                    <td class="selection__text">{{ $worker->{'name' . $prefix} }}</td>

                                    <td class="selection__text">{{ $worker->email }}</td>

                                    <td class="selection__text">{{ \Carbon\Carbon::parse($worker->created_at)->format('d.m.Y')}}</td>

                                    <td class="selection__text">{{ $worker->role }}</td>

                                    <td class="selection__text">{{ $worker->status }}</td>

                                    <td class="selection__text">
                                        <a href="{{ route('user.show', ($worker->slug) ? $worker->slug : $worker->id) }}">
                                            <button class="selection__eye selection__button"></button>
                                        </a>
                                    </td>

                                    <td class="selection__text">
                                        <a href="{{ route('user.edit', $worker->id) }}">
                                            <button class="selection__pencil selection__button"></button>
                                        </a>
                                    </td>

                                    <td class="selection__text">
                                        {!! Form::open(['url' => route('user.destroy',$worker->id),'method' => 'POST', 'class' => 'btn', 'role' => 'form','onsubmit' => 'return confirm("' . $confirm . '")'])!!}
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="selection__delete selection__button"></button>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty">@lang('site.empty')</div>
                    @endif
                </div>
            </section>
        </div>
    </div>
@endsection
