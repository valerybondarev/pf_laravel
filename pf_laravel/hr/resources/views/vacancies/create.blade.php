@extends('layout.site', ['title' => __('site.new_vacancie')])

@section('content')

    @php
        $lang = session()->get('locale');
        $prefix = ($lang == 'ru') ? '' : '_' . $lang;
    @endphp

    <section class="candidates">
        <h2 class="candidates__heading heading">@lang('site.new_vacancie')</h2>
    </section>

    <form method="post" action="@guest{{ route('vacancie') }}@else{{ route('vacancie.store') }}@endif" enctype="multipart/form-data">
        @csrf

    <div class="container">

        <section class="skill">
            <div class="skill__unit">
                <div class="skill__part">
                    <label class="personal__label label" for="fam">@lang('site.vacancie')</label>
                    <input class="personal__input personal__input--first input" type="text" id="fam" name="name{{ $prefix }}"
                           placeholder="@lang('site.vacancie')" value="{{ ($vacancie->name) ? $vacancie->name : old('name' . $prefix) ?? '' }}" required>
                </div>
                <div class="skill__part">
                    <label class="personal__label label" for="specialization_id">@lang('site.specialization')</label>
                    <select class="personal__input personal__input--second input" name="specialization_id" id="specialization_id">
                        <option></option>
                        @foreach($specializations as $specialization)
                            <option value="{{ $specialization->id }}" {{ old('specialization_id') && old('specialization_id') == $specialization->id ? 'selected' : ''}}>
                                {{ $specialization->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="skill__part">
                    <label class="skill__label label" for="salary_from">@lang('site.salary_from')</label>
                    <input class="skill__input input" type="text" id="salary_from" name="salary_from{{ $prefix }}" value="{{ old('salary_from'. $prefix) ?? '' }}" placeholder="@lang('site.salary_from')">
                </div>
                <div class="skill__part">
                    <label class="skill__label label" for="salary_to">@lang('site.salary_to')</label>
                    <input class="skill__input input" type="text" id="salary_to" name="salary_to{{ $prefix }}" value="{{ old('salary_to'. $prefix) ?? '' }}" placeholder="@lang('site.salary_to')">
                </div>
                <div class="skill__part">
                    <label class="personal__label personal__label--second label" for="gender">@lang('site.taxes')</label>
                    <select class="personal__input personal__input--second input" name="taxes" id="taxes" required>
                        <option value="@lang('site.before_taxes')">@lang('site.before_taxes')</option>
                        <option value="@lang('site.after_taxes')">@lang('site.after_taxes')</option>
                    </select>
                </div>
                <div class="skill__part">
                    <label class="skill__label label" for="experience">@lang('site.experience')</label>
                    <input type="radio" name="experience{{ $prefix }}" value="@lang('site.experience_val1')" checked> @lang('site.experience_val1')<br>
                    <input type="radio" name="experience{{ $prefix }}" value="@lang('site.experience_val2')"> @lang('site.experience_val2')<br>
                    <input type="radio" name="experience{{ $prefix }}" value="@lang('site.experience_val3')"> @lang('site.experience_val3')<br>
                    <input type="radio" name="experience{{ $prefix }}" value="@lang('site.experience_val4')"> @lang('site.experience_val4')
                </div>
                <div class="skill__part">
                    <label class="skill__label label" for="skills">@lang('site.choose_skills')</label>
                    <select class="skill__input input select2-multiple" name="skills[]"
                            id="skill" multiple>
                        <option>@lang('site.choose_skills')</option>
                        @foreach($skills as $skill)
                            <option value="{{ $skill->id }}">{{ $skill->{'name' . $prefix} }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
           <div class="skill__unit">
                <div class="textarea-div">
                    <label class="skill__label label" for="requirements">@lang('site.vacancie_txt')</label>
                    <textarea cols="2" rows="10" class="skill__input input summernote_editor" name="requirements{{ $prefix }}" id="requirements" required>
                        <p><b>@lang('site.vacancie_txt1')</b></p>
                        <p>-</p>
                        <p><b>@lang('site.vacancie_txt2')</b></p>
                        <p>-</p>
                        <p><b>@lang('site.vacancie_txt3')</b></p>
                        <p>-</p>
                    </textarea>
                </div>
            </div>
        </section>
    </div>

    <section class="registration">
        <button type="submit" class="registration__btn button">@lang('site.add_vacancie')</button>
    </section>

    </form>

@endsection
