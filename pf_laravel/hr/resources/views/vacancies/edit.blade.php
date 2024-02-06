@extends('layout.site', ['title' => __('site.vacancie')])

@section('content')

    @php
        $lang = session()->get('locale');
        $prefix = ($lang == 'ru') ? '' : '_' . $lang;
    @endphp

    <section class="candidates">
        <h2 class="candidates__heading heading">{{ __('site.vacancie', ['name' => $vacancie->name]) }}</h2>
    </section>

    {{ Form::open(array('url' => route('vacancie.update', $vacancie->id), 'method' => 'PUT', 'class'=>'col-md-12',  'enctype' => "multipart/form-data")) }}
    @method('put')
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
                            <option value="{{ $specialization->id }}" {{ ($vacancie->specialization_id == $specialization->id) ? 'selected' : '' }}>
                                {{ $specialization->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="skill__part">
                    <label class="skill__label label" for="salary_from">@lang('site.salary_from')</label>
                    <input class="skill__input input" type="text" id="salary_from" name="salary_from{{ $prefix }}" value="{{ $vacancie->{'salary_from'. $prefix} }}" placeholder="@lang('site.salary_from')">
                </div>
                <div class="skill__part">
                    <label class="skill__label label" for="salary_to">@lang('site.salary_to')</label>
                    <input class="skill__input input" type="text" id="salary_to" name="salary_to{{ $prefix }}" value="{{ $vacancie->{'salary_to'. $prefix} }}" placeholder="@lang('site.salary_to')">
                </div>
                <div class="skill__part">
                    <label class="personal__label personal__label--second label" for="gender">@lang('site.taxes')</label>
                    <select class="personal__input personal__input--second input" name="taxes" id="taxes" required>
                        <option value="@lang('site.before_taxes')" {{ ($vacancie->{'taxes'} == __('site.before_taxes')) ? 'selected' : '' }}>@lang('site.before_taxes')</option>
                        <option value="@lang('site.after_taxes')" {{ ($vacancie->{'taxes'} == __('site.after_taxes')) ? 'selected' : '' }}>@lang('site.after_taxes')</option>
                    </select>
                </div>
                <div class="skill__part">
                    <label class="skill__label label" for="experience">@lang('site.experience')</label>
                    <input type="radio" name="experience{{ $prefix }}" value="@lang('site.experience_val1')" @if (isset($vacancie->skills) && $vacancie->{'experience'. $prefix}==__('site.experience_val1')) checked @endif> @lang('site.experience_val1')<br>
                    <input type="radio" name="experience{{ $prefix }}" value="@lang('site.experience_val2')" @if (isset($vacancie->skills) && $vacancie->{'experience'. $prefix}==__('site.experience_val2')) checked @endif> @lang('site.experience_val2')<br>
                    <input type="radio" name="experience{{ $prefix }}" value="@lang('site.experience_val3')" @if (isset($vacancie->skills) && $vacancie->{'experience'. $prefix}==__('site.experience_val3')) checked @endif> @lang('site.experience_val3')<br>
                    <input type="radio" name="experience{{ $prefix }}" value="@lang('site.experience_val4')" @if (isset($vacancie->skills) && $vacancie->{'experience'. $prefix}==__('site.experience_val4')) checked @endif> @lang('site.experience_val4')
                </div>
                <div class="skill__part">
                    <label class="skill__label label" for="skills">@lang('site.choose_skills')</label>
                    <select class="skill__input input select2-multiple" name="skills[]"
                            id="skill" multiple>
                        <option>@lang('site.choose_skills')</option>
                        @foreach($skills as $skill)
                            <option value="{{ $skill->id }}" @if(isset($vacancie->skills) && !empty($vacancie->skills) && in_array($skill->id, explode(',', $vacancie->skills))) selected @endif>{{ $skill->{'name' . $prefix} }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
           <div class="skill__unit">
                <div class="textarea-div">
                    <label class="skill__label label" for="requirements">@lang('site.vacancie_txt')</label>
                    <textarea cols="2" rows="10" class="skill__input input summernote_editor" name="requirements{{ $prefix }}" id="requirements" required>
                        {!! $vacancie->{'requirements' . $prefix} !!}
                    </textarea>
                </div>
            </div>
        </section>
    </div>

    <section class="registration">
        <button type="submit" class="registration__btn button">@lang('site.save')</button>
    </section>

    {{ Form::close() }}

@endsection
