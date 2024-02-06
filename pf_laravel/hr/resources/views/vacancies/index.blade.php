@extends('layout.site', ['title' => __('site.vacancies')])

@section('content')

    @php
        $confirm = __('site.vacancie_confirm');
        $lang = session()->get('locale');
        $prefix = ($lang == 'ru') ? '' : '_' . $lang;
    @endphp

    <section class="candidates">
        <h2 class="candidates__heading heading">@lang('site.vacancies')</h2>
        <a class="candidates__link button" href="{{ route('vacancie.create') }}">@lang('site.new_vacancie')</a>
    </section>

    <section class="tabs">
        <ul class="tabs__list filter__candidate">
            <li class="tabs__item">
                <button class="tabs__btn tabs__btn--active" data-tabs-path="filter">@lang('site.filter')</button>
            </li>
       </ul>
    </section>



    <div class="tabs__content tabs__content--active" data-tabs-target="filter">
        <div class="content">
            <section class="filter">
                <form id="form-user-filter" action="{{ route('vacancie') }}" method="GET">
                    <div class="filter__container">

                        <label class="filter__label filter__label--first label" for="name">@lang('site.search')</label>
                        <label class="filter__label filter__label--second label" for="skill">@lang('site.skills')</label>
                        <label class="filter__label filter__label--third label" for=""></label>
                        <input class="filter__input filter__input--first input" type="text" id="name" name="name"
                               placeholder="@lang('site.search_vacancie_placeholder')"  value="{{ $filter['name'] ?? '' }}">
                        <select class="filter__input filter__input--second input select2-multiple" name="skills[]"
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
                    @if(!empty($vacancies))
                        <table class="selection__table">
                            <tbody>
                            <tr class="selection__block">

                                <td class="selection__text pl-3">@lang('site.num')</td>

                                <td class="selection__text">@lang('site.vacancie')</td>

                                <td class="selection__text">@lang('site.specialization')</td>

                                <td class="selection__text">@lang('site.created_at')</td>

                                <td class="selection__text">@lang('site.salary_from')</td>

                                <td class="selection__text">@lang('site.salary_to')</td>

                                <td class="selection__text">@lang('site.taxes')</td>

                                <td class="selection__text">@lang('site.experience')</td>

                                <td class="selection__text">@lang('site.skills')</td>

                                <td class="selection__none"></td>

                                <td class="selection__none"></td>

                                <td class="selection__none"></td>

                            </tr>

                            @foreach($vacancies as $key => $vacancie)

                                <tr>

                                    <td class="selection__text pl-3">
                                        {{ ++$key }}
                                    </td>

                                    <td class="selection__text">{{ $vacancie->{'name' . $prefix} }}</td>

                                    <td class="selection__text">{{ $vacancie->{'specialization' . $prefix} }}</td>

                                    <td class="selection__text">{{ \Carbon\Carbon::parse($vacancie->created_at)->format('d.m.Y')}}</td>

                                    <td class="selection__text">{{ $vacancie->{'salary_from' . $prefix} }}</td>
                                    <td class="selection__text">{{ $vacancie->{'salary_to' . $prefix} }}</td>
                                    <td class="selection__text">{{ $vacancie->{'taxes' . $prefix} }}</td>
                                    <td class="selection__text">{{ $vacancie->{'experience' . $prefix} }}</td>
                                    <td class="selection__text">
                                        <div class="candidate__fillter__item__tag">
                                                @foreach($skills as $skill)
                                                    @if(isset($vacancie->skills) && !empty($vacancie->skills) && in_array($skill->id, explode(',',$vacancie->skills)))
                                                        <span>{{ $skill->{'name' . $prefix} }}</span>
                                                    @endif
                                                @endforeach
                                            <span>&nbsp;</span>
                                        </div>
                                    </td>

                                    <td class="selection__text">
                                        <a href="{{ route('vacancie.show', $vacancie->id) }}">
                                            <button class="selection__eye selection__button"></button>
                                        </a>
                                    </td>

                                    <td class="selection__text">
                                        <a href="{{ route('vacancie.edit', $vacancie->id) }}">
                                            <button class="selection__pencil selection__button"></button>
                                        </a>
                                    </td>

                                    <td class="selection__text">
                                        {!! Form::open(['url' => route('vacancie.destroy',$vacancie->id),'method' => 'POST', 'class' => 'btn', 'role' => 'form','onsubmit' => 'return confirm("' . $confirm . '")'])!!}
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
