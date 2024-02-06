@extends('layout.site', ['title' => __('site.user')])

@section('content')

    <section class="candidates">
        <h2 class="candidates__heading heading">{{ __('site.user_name', ['name' => $worker->name]) }}</h2>
    </section>

    @php
        $lang = session()->get('locale');
        $prefix = ($lang == 'ru') ? '' : '_' . $lang;
        //print_r($worker->worker->skills);
    @endphp

    <div class="container">

        {{ Form::open(array('url' => route('user.update', $worker->id), 'method' => 'PUT', 'class'=>'col-md-12',  'enctype' => "multipart/form-data")) }}
            @method('put')
            @csrf

        <h2 class="head">@lang('site.edit_user')</h2>
        <section class="personal">
            <div class="personal__container">

                <input hidden class="personal__input personal__input--first input" type="text" id="fam" name="name{{ $prefix }}"
                       placeholder="@lang('site.name_surname')" value="{{ $worker->{'name' . $prefix} }}">

                <label class="personal__label label" for="f_n">@lang('site.first_name')</label>
                <input class="personal__input personal__input--first input" type="text" id="f_n" name="first_name{{ $prefix }}"
                       placeholder="@lang('site.first_name')" value="{{ $worker->{'first_name' . $prefix} }}" required>

                <label class="personal__label label" for="l_n">@lang('site.last_name')</label>
                <input class="personal__input personal__input--first input" type="text" id="l_n" name="last_name{{ $prefix }}"
                       placeholder="@lang('site.last_name')" value="{{ $worker->{'last_name' . $prefix} }}">

                <label class="personal__label label" for="m_n">@lang('site.middle_name')</label>
                <input class="personal__input personal__input--first input" type="text" id="m_n" name="middle_name{{ $prefix }}"
                       placeholder="@lang('site.middle_name')" value="{{ $worker->{'middle_name' . $prefix} }}">

                <label class="personal__label label" for="mail">@lang('site.email')</label>
                <input class="personal__input personal__input--first input" type="email" id="mail" name="email"
                       placeholder="@lang('site.address_email')" value="{{ $worker->email }}" required>

                <div class="personal__unit">
                    <div class="personal__part">
                        <label class="personal__label personal__label--second label" for="gender">@lang('site.sex')</label>
                        <select class="personal__input personal__input--second input" name="sex{{ $prefix }}" id="gender" required>
                            <option>@lang('site.sex')</option>
                            <option value="@lang('site.sex_M')" {{ ($worker->worker->{'sex' . $prefix} == __('site.sex_M')) ? 'selected' : '' }}>@lang('site.sex_M')</option>
                            <option value="@lang('site.sex_F')" {{ ($worker->worker->{'sex' . $prefix} == __('site.sex_F')) ? 'selected' : '' }}>@lang('site.sex_F')</option>
                        </select>
                        <!--input class="personal__input personal__input--second input" type="text" id="gender" name="sex"
                               placeholder="Выберите пол" required-->
                    </div>
                    <div class="personal__part">
                        <label class="personal__label personal__label--second label" for="date">@lang('site.birthday')</label>
                        <input class="personal__input personal__input--second input" type="date" id="date" name="birthday"
                               placeholder="@lang('site.date_format')" value="{{ $worker->worker->birthday }}" >
                    </div>
                </div>
            </div>
            <div class="personal__block">
                <p class="personal__photo">@lang('site.photo')</p>
                <input type="file" name="photo" class="personal_photo personal__btn" @if($worker->worker->photo) style="background-image: url({{ url('storage/'.$worker->worker->photo) }})" @endif>
            </div>
        </section>

        <h2 class="head">@lang('site.password')</h2>
        <section class="password">
            <div class="password__unit">
                <div class="password__part">
                    <label class="password__label label" for="password">@lang('site.password')</label>
                    <input class="password__input input" type="password" id="password" name="password" placeholder="@lang('site.new_password')">
                </div>
                <div class="password__part">
                    <label class="password__label label" for="password2">@lang('site.password')</label>
                    <input class="password__input input" type="password" id="password2" name="password_confirmation" placeholder="@lang('site.confirm_password')">
                </div>
            </div>
        </section>

        <h2 class="head">@lang('site.contact')</h2>
        <section class="contact">
            <div class="contact__unit">
                <div class="contact__part">
                    <label class="contact__label label" for="district">@lang('site.district')</label>
                    <input class="contact__input input" type="text" id="district" name="region{{ $prefix }}" value="{{ $worker->worker->{'region' . $prefix} }}" placeholder="@lang('site.you_district')">
                </div>
                <div class="contact__part">
                    <label class="contact__label label" for="whatsapp">@lang('site.watsapp')</label>
                    <input class="contact__input input" type="text" id="watsapp" name="watsapp" value="{{ $worker->worker->watsapp }}" placeholder="+7 (___) ___ __ __">
                </div>
                <div class="contact__part">
                    <label class="contact__label label" for="telephone">@lang('site.phone')</label>
                    <input class="contact__input input" type="text" id="telephone" name="phone" value="{{ $worker->worker->phone }}" placeholder="+7 (___) ___ __ __">
                </div>
                <div class="contact__part">
                    <label class="contact__label label" for="viber">@lang('site.viber')</label>
                    <input class="contact__input input" type="text" id="viber" name="vyber" value="{{ $worker->worker->vyber }}" placeholder="+7 (___) ___ __ __">
                </div>
                <div class="contact__part">
                    <label class="contact__label label" for="telegram">@lang('site.telegram')</label>
                    <input class="contact__input input" type="text" id="telegram" name="telegram" value="{{ $worker->worker->telegram }}" placeholder="@">
                </div>
                <div class="contact__part">
                    <label class="contact__label label" for="skype">@lang('site.skype')</label>
                    <input class="contact__input input" type="text" id="skype" name="skype" value="{{ $worker->worker->skype }}" placeholder="@lang('site.skype')">
                </div>
            </div>
        </section>

        <h2 class="head">@lang('site.profile_data')</h2>
        <section class="skill">
            <div class="skill__unit">
                <div class="skill__part">
                    <label class="skill__label label" for="summary">@lang('site.hh')</label>
                    <input class="skill__input input" type="text" id="summary" name="resume" value="{{ $worker->worker->resume }}" placeholder="@lang('site.hh_link')">
                </div>
                <div class="skill__part">
                    <label class="skill__label label" for="education">@lang('site.education')</label>
                    <input class="skill__input input" type="text" id="education" name="education{{ $prefix }}" value="{{ $worker->worker->{'education' . $prefix} }}" placeholder="@lang('site.you_education')">
                </div>
                <div class="skill__part">
                    <label class="skill__label label" for="experience">@lang('site.experience')</label>
                    <!--textarea cols="2" rows="10" class="skill__input input" name="experience{{ $prefix }}" id="experience">{{ $worker->worker->{'experience' . $prefix} }}</textarea-->
                    <input class="skill__input input" type="text" id="experience" name="experience{{ $prefix }}" value="{{ $worker->worker->{'experience' . $prefix} }}" placeholder="@lang('site.you_experience')">
                </div>
                <div class="skill__part">
                    <label class="skill__label label" for="specialization_id">@lang('site.specialization')</label>
                    <select class="personal__input personal__input--second input" name="specialization_id" id="specialization_id">
                        <option></option>
                        @foreach($specializations as $specialization)
                            <option value="{{ $specialization->id }}" {{ ($worker->worker->specialization_id == $specialization->id) ? 'selected' : '' }}>
                                {{ $specialization->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="skill__part">
                    <label class="skill__label label" for="level_id">@lang('site.level')</label>
                    <select class="personal__input personal__input--second input" name="level_id" id="level_id">
                        <option></option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ ($worker->worker->level_id == $level->id) ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="skill__part">
                    <label class="skill__label label" for="skills">@lang('site.choose_skills')</label>
                    <select class="skill__input input select2-multiple" name="skills[]"
                            id="skill" multiple>
                        <option>@lang('site.choose_skills')</option>
                        @foreach($skills as $skill)
                            <option value="{{ $skill->id }}" @if(isset($worker->worker->skills) && !empty($worker->worker->skills) && in_array($skill->id, explode(',', $worker->worker->skills))) selected @endif>{{ $skill->{'name' . $prefix} }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="skill__part">
                    <label class="skill__label label" for="skills">@lang('site.languages')</label>
                    <select class="skill__input input select2-multiple" name="languages[]"
                            id="language" multiple>
                        <option>@lang('site.languages')</option>
                        @foreach($languages as $language)
                            <option value="{{ $language->id }}" @if(!empty($worker->worker->languages) && in_array($language->id, explode(',', $worker->worker->languages))) selected @endif>{{ $language->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="skill__part" id="language_levels_wrap">
                    <label class="skill__label label" for="skills">@lang('site.language_levels')</label>
                    <select id="hidden_language_levels" style="display: none">
                        <option value="0"></option>
                        @foreach($languageLevels as $languageLevel)
                            <option value="{{ $languageLevel->id }}"> {{ $languageLevel->name }} </option>
                        @endforeach
                    </select>
                    @if (!empty($worker->worker->language_levels))
                        @foreach(explode(',', $worker->worker->language_levels) as $languageLevelId)
                            <select class="language-levels personal__input input" name="language_levels[]">
                                <option></option>
                                @foreach($languageLevels as $languageLevel)
                                    <option
                                        value="{{ $languageLevel->id }}"
                                        {{ $languageLevel->id == $languageLevelId ? 'selected' : ''}}
                                    >
                                        {{ $languageLevel->name }}
                                    </option>
                                @endforeach
                            </select>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>

        <div class="skill__unit">
            <div class="textarea-div">
                <label class="skill__label label" for="comment">@lang('site.comment')</label>
                <textarea cols="2" rows="10" class="skill__input input" name="comment{{ $prefix }}" id="comment" style="min-height: 10rem;">{{ $worker->worker->{'comment' . $prefix} }}</textarea>
            </div>
        </div>

        <h2 class="head">@lang('site.experience_data')</h2>
        <section class="skill">
            <div class="skill__unit">
                <div class="skill__part">
                    <label class="skill__label label" for="status_id">@lang('site.experiences')</label>
                </div>
                <div class="skill__part">
                    <ul id="experienceData" class="">
                        @foreach($worker->experiences as $experience)
                            <li class="experience-item">{{ $experience->company }} <a class="experience-delete" href="javascript:void(0)" data-id="{{ $experience->id }}"><img src="/img/delete.svg"/></a><input name="experiences[]" type="hidden" value="{{ $experience->id }}"></li>
                        @endforeach
                    </ul>
                    <a data-toggle="modal" id="newExperienceButton" data-target="#experienceModal" title="show" class="experience__btn button">@lang('site.new_experience')</a>
                </div>
            </div>
        </section>

{{--        <h2 class="head">@lang('site.service_data')</h2>--}}
{{--        <section class="skill">--}}
{{--            <div class="skill__unit">--}}
{{--                <div class="skill__part">--}}
{{--                    <label class="skill__label label" for="status_id">@lang('site.status')</label>--}}
{{--                    <select class="skill__input input" name="status_id" id="status_id">--}}
{{--                        <option>@lang('site.choose_status')</option>--}}
{{--                        @foreach($statuses as $status)--}}
{{--                            <option value="{{ $status->id }}" {{ ($worker->worker->status->id == $status->id) ? 'selected' : '' }}>{{ $status->{'name' . $prefix} }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--                <div class="skill__part">--}}
{{--                    <label class="skill__label label" for="role_id">@lang('site.role')</label>--}}
{{--                    <select class="skill__input input" name="role_id" id="role_id">--}}
{{--                        <option>@lang('site.choose_role')</option>--}}
{{--                        @foreach($roles as $role)--}}
{{--                            <option value="{{ $role->id }}" {{ ($worker->role->id == $role->id) ? 'selected' : '' }}>{{ $role->{'name' . $prefix} }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="skill__unit">--}}
{{--                <div class="textarea-div">--}}
{{--                    <label class="skill__label label" for="comment">@lang('site.documents')</label>--}}
{{--                    @if($worker->documents)--}}
{{--                        <div>--}}
{{--                        @foreach($worker->documents as $document)--}}
{{--                            <a href="{{ $document->url }}">{{ $document->name }}</a>--}}
{{--                        @endforeach--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                    <input type="file" class="skill__input input" name="documents[]" id="documents" multiple/>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--        </section>--}}

        <section class="registration">
            <button type="submit" class="registration__btn button">@lang('site.save')</button>
        </section>

    </div>

    <input type="hidden" name="user_id" value="{{ $worker->worker->user_id }}">
    {{ Form::close() }}

    @include('user.experience')
@endsection

