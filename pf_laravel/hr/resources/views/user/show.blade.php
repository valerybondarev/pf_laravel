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

        <div class="col-md-12">

        <!--h2 class="head">@lang('site.edit_user')</h2-->
            <section class="personal">

                <div class="personal__container">
                    <div class="personal__label label">@lang('site.name_surname'): <span
                            class="personal__item">{{ $worker->{'name' . $prefix} }}</span></div>

                    <div class="personal__label label">@lang('site.email'): <span
                            class="personal__item">{{ $worker->email }}</span></div>

                    <div class="personal__label label">@lang('site.gender'): <span
                            class="personal__item">{{ $worker->worker->{'sex' . $prefix} }}</span></div>

                    <div class="personal__label label">@lang('site.birthday'): <span
                            class="personal__item">{{ \Carbon\Carbon::parse($worker->worker->birthday)->format('d.m.Y') }}</span></div>

                </div>

                <div class="personal__block">
                    <p class="personal__photo">@lang('site.photo')</p>
                    <div class="personal_photo personal__img"
                         @if($worker->worker->photo) style="background-image: url({{ url('storage/'.$worker->worker->photo) }})" @endif></div>
                </div>

            </section>

            @if($worker->worker->{'region' . $prefix} || $worker->worker->watsapp || $worker->worker->phone || $worker->worker->vyber || $worker->worker->telegram || $worker->worker->skype)
                <h2 class="head">@lang('site.contact')</h2>
            @endif
            <section class="contact">
                <div class="contact__unit">
                    @if($worker->worker->{'region' . $prefix})
                        <div class="contact__part">
                            <div class="personal__label label">@lang('site.district'): <span
                                    class="personal__item">{{ $worker->worker->{'region' . $prefix} }}</span></div>
                        </div>
                    @endif
                    @if($worker->worker->watsapp)
                        <div class="contact__part">
                            <div class="personal__label label">@lang('site.watsapp'): <span
                                    class="personal__item">{{ $worker->worker->watsapp }}</span></div>
                        </div>
                    @endif
                    @if($worker->worker->phone)
                        <div class="contact__part">
                            <div class="personal__label label">@lang('site.phone'): <span
                                    class="personal__item">{{ $worker->worker->phone }}</span></div>
                        </div>
                    @endif
                    @if($worker->worker->vyber)
                        <div class="contact__part">
                            <div class="personal__label label">@lang('site.viber'): <span
                                    class="personal__item">{{ $worker->worker->vyber }}</span></div>
                        </div>
                    @endif
                    @if($worker->worker->telegram)
                        <div class="contact__part">
                            <div class="personal__label label">@lang('site.telegram'): <span
                                    class="personal__item">{{ $worker->worker->telegram }}</span></div>
                        </div>
                    @endif
                    @if($worker->worker->skype)
                        <div class="contact__part">
                            <div class="personal__label label">@lang('site.skype'): <span
                                    class="personal__item">{{ $worker->worker->skype }}</span></div>
                        </div>
                    @endif

                </div>
            </section>
            @if($worker->worker->resume || $worker->worker->{'education' . $prefix} || $worker->worker->{'experience' . $prefix} || !empty($worker->worker->skills))
                <h2 class="head">@lang('site.profile_data')</h2>
            @endif
            <section class="skill">
                <div class="skill__unit">
                    @if($worker->worker->resume)
                        <div class="skill__part">
                            <div class="personal__label label">@lang('site.hh'): <span
                                    class="personal__item"><a target="_blank" href="{{ $worker->worker->resume }}">@lang('site.link')</a></span></div>
                        </div>
                    @endif
                    @if($worker->worker->{'education' . $prefix})
                        <div class="skill__part">
                            <div class="personal__label label">@lang('site.education'): <span
                                    class="personal__item">{{ $worker->worker->{'education' . $prefix} }}</span></div>
                        </div>
                    @endif
                    @if($worker->worker->{'experience' . $prefix})
                        <div class="skill__part">
                            <div class="personal__label label">@lang('site.experience'): <span
                                    class="personal__item">{{ $worker->worker->{'experience' . $prefix} }}</span></div>
                        </div>
                    @endif

                    @if(!empty($worker->worker->skills))
                        <div class="skill__part">
                            <div class="skill__label label" for="skills">@lang('site.skills'):</div>
                            <ul class="personal__item">
                                @foreach($skills as $skill)
                                    @if(isset($worker->worker->skills) && !empty($worker->worker->skills) && in_array($skill->id, explode(',', $worker->worker->skills)))
                                        <li class="skill-item">{{ $skill->{'name' . $prefix} }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </section>

            <h2 class="head">@lang('site.experience_data')</h2>
            <section class="skill">
                <div class="skill__unit">
                    <div class="skill__part">
                        <label class="skill__label label" for="status_id">@lang('site.experiences')</label>
                    </div>
                    <div class="skill__part">
                        <ul id="experienceData" class="">
                            @foreach($worker->experiences as $experience)
                                <li class="experience-item">
                                    <div class="experience-company">{{ $experience->{'company' . $prefix} }}</div>
                                    <div class="experience-date experience-small">c {{ \Carbon\Carbon::parse($experience->date_start)->format('d.m.Y') }}
                                        @if($experience->current == 1)
                                            @lang('site.current_time')
                                        @else
                                            по {{ \Carbon\Carbon::parse($experience->date_end)->format('d.m.Y') }}
                                        @endif
                                    </div>
                                    <div class="experience-position experience-small">{{ $experience->{'position' . $prefix} }}</div>
                                    <div class="experience-duties experience-small">{{ $experience->{'duties' . $prefix} }}</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </section>

            <h2 class="head">@lang('site.service_data')</h2>
            <section class="skill">
                <div class="skill__unit">
                    <div class="skill__part">
                        <div class="skill__label label" for="status_id">@lang('site.status'): <span
                                class="personal__item">{{ $worker->worker->status->{'name' . $prefix} }}</span></div>
                    </div>
                    <div class="skill__part">
                        <div class="skill__label label" for="role_id">@lang('site.role'): <span
                                class="personal__item">{{ $worker->role->{'name' . $prefix} }}</span></div>
                    </div>
                </div>
                @if($worker->worker->{'comment' . $prefix})
                    <div class="skill__unit">
                        <div class="textarea-div">
                            <div class="skill__label label" for="comment">@lang('site.comment'): <span
                                    class="personal__item">{{ $worker->worker->{'comment' . $prefix} }}</span></div>
                        </div>
                    </div>
                @endif
                @if(!$worker->documents->isEmpty())
                    <div class="skill__unit">
                        <div class="skill__part">
                            <div class="skill__label label" for="comment">@lang('site.documents'):</div>
                            <ul class="personal__item">
                                @foreach($worker->documents as $document)
                                    <li class="document-item"><a href="{{ $document->url }}">{{ $document->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </section>


        </div>

    </div>

@endsection

