@extends('layout.site', ['title' => __('site.vacancie')])

@section('content')

    @php
        $lang = session()->get('locale');
        $prefix = ($lang == 'ru') ? '' : '_' . $lang;
    @endphp

    <section class="candidates">
        <h2 class="candidates__heading heading">{{ __('site.vacancie', ['name' => $vacancie->name]) }}</h2>
    </section>


    <div class="container">

        <section class="content">
            <div class="skill__unit">
                <div class="text-nowrap personal__label label">
                    <div class="personal__label label">
                        @lang('site.vacancie'):
                        <span class="personal__item">{{ $vacancie->{'name' . $prefix} }}</span>
                    </div>
                </div>
            </div>
            <div class="skill__unit">
                <div class="text-nowrap">
                    <div class="personal__label label">
                        @lang('site.specialization'):
                        <span class="personal__item">{{ $vacancie->specialization->name }}</span>
                    </div>
                </div>
            </div>
            <div class="skill__unit">
                <div class="text-nowrap">
                    <div class="personal__label label">
                        @lang('site.salary_from'):
                        <span class="personal__item">{{ $vacancie->{'salary_from' . $prefix} }}</span>
                    </div>
                </div>
            </div>
            <div class="skill__unit">
                <div class="text-nowrap">
                    <div class="personal__label label">
                        @lang('site.salary_to'):
                        <span class="personal__item">{{ $vacancie->{'salary_to' . $prefix} }}</span>
                    </div>
                </div>
            </div>
            <div class="skill__unit">
                <div class="text-nowrap">
                    <div class="personal__label label">
                        @lang('site.taxes'):
                        <span class="personal__item">{{ $vacancie->{'taxes'} }}</span>
                    </div>
                </div>
            </div>
            <div class="skill__unit">
                <div class="text-nowrap">
                    <div class="personal__label label">
                        @lang('site.experience'):
                        <span class="personal__item">{{ $vacancie->{'experience' . $prefix} }}</span>
                    </div>
                </div>
            </div>
            <div class="skill__unit">
                @if(!empty($vacancie->skills))
                    <div class="text-nowrap">
                        <div class="skill__label label" for="skills">
                            @lang('site.skills'):
                        </div>
                        <ul class="personal__item">
                            @foreach($skills as $skill)
                                @if(isset($vacancie->skills) && !empty($vacancie->skills) && in_array($skill->id, explode(',',$vacancie->skills)))
                                    <li class="skill-item">{{ $skill->{'name' . $prefix} }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif

            </div>
           <div class="skill__unit">
               <div class="personal__label label">
                   @lang('site.vacancie_txt'):
                   {!! $vacancie->{'requirements' . $prefix} !!}
               </div>

            </div>
        </section>
    </div>

@endsection
