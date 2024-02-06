@extends('layout.candidate')
@section('content')
<section class="content">
    <div class="container">
        <a href="{{ route('candidate') }}" class="all-specialists">@lang('site.all_professionals')</a>
        <div class="candidate-edit">
            <div class="candidate-edit__head">
                <div class="candidate-edit__intro">
                    <div class="candidate-edit__top">
                        <h1 class="candidate-edit__name">{{ $user->first_name }}</h1>
                        <div class="candidate-edit__gender">
                            @if (!empty($user->worker->sex))
                                {{ $user->worker->sex === 'М' ? __('site.sex_male') : __('site.sex_female')}},
                            @endif
                            @if ($age)
                                {{ $age }} @lang('site.years')
                            @endif
                        </div>
                    </div>
                    <div class="candidate-edit__stack">
                        {{ !empty($user->worker->specialization) ? $user->worker->specialization->{'name'.$locale} : ''}}
                    </div>
                    <table class="candidate-edit__table-work">
                        <tr>
                            <td>@lang('site.level')</td>
                            <td>{{ !empty($user->worker->level) ? $user->worker->level->{'name'.$locale} : ''}}</td>
                        </tr>
                        <tr>
                            <td>@lang('site.experience')</td>
                            <td>
                                @if ($user->worker['experience'])
                                    {{ $user->worker['experience'] }} @lang('site.years')
                                @endif
                            </td>
                        </tr>
                    </table>
                    <a href="#" class="hire hire-spec btn">@lang('site.hire_professional')</a>
                </div>
                <div class="candidate-edit__img">
                    @if (!empty($user->worker['photo']))
                        <img src="{{ url('storage/'.$user->worker->photo) }}" alt="">
                    @else
                        <img src="{{ asset('/img/candidate_photo.jpg') }}" alt="">
                    @endif
                </div>

            </div>


            <div class="candidate-edit__body">
                <div class="candidate-edit__title">@lang('site.key_skills')</div>
                <div class="candidate-edit__tag">
                    @if ($user->worker['skills'])
                        @foreach (explode(',', $user->worker['skills']) as $skillId)
                            <span>{{ $skills[$skillId]->{'name'.$locale} }}</span>
                        @endforeach
                    @endif
                </div>
                <table class="candidate-edit__table">
                    <tr>
                        <td>
                            @lang('site.about_me')
                            <p>{{ $user->worker->{'comment'.$locale} }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="about-header">@lang('site.experience')</div>
                            @if (!empty($user->experiences))
                                @foreach ($user->experiences as $experience)
                                    <div class="candidate-edit__title-small"> {{ $experience->position }}</div>
                                    <p>
                                        <strong>
                                            {{ $experience['date_start'] }}
                                            @if (!empty($experience['date_start']) && !empty($experience['date_end']))
                                                —
                                            @endif
                                            {{ $experience['date_end'] }}
                                            @if((!empty($experience['date_start']) || !empty($experience['date_end']) && !empty($experience->company)))
                                                |
                                            @endif
                                        </strong>
                                        {{ $experience->{'company'.$locale} }}
                                    </p>
                                    <p class="experience-description">{!! $experience->{'duties'.$locale} !!}</p>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @lang('site.education')
                            <div class="candidate-edit__title-small">
                                {{ $user->worker->{'education'.$locale} }} &nbsp;
                            </div>
                            <!--
                            <p><strong>Санкт-Петербургский государственный политехнический университет</strong> </p>
                            <p>Разработка программного обеспечения</p>
                            -->
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @lang('site.languages')
                            <table id="language-levels">
                                @if (!empty($user->worker->languages))
                                    @foreach(explode(',', $user->worker->languages) as $languageId)
                                        <tr>
                                            <td class="language">
                                                <span class="lang-name">{{ isset($languages[$languageId]) ? $languages[$languageId]->{'name'.$locale} : '' }}</span>
                                                @if (!empty($languageLevels[$loop->index]))
                                                    – {{ $languageLevelsList[$languageLevels[$loop->index]]->{'name'.$locale} }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
