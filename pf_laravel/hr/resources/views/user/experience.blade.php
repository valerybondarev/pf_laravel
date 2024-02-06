<div class="modal fade" id="experienceModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4>@lang('site.new_place_work')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mediumBody">
                <div>
                    <form method="post" action="" id="formExperience">
                        <section class="skill">

                            <div class="experience__unit">
                                <label class="personal__label personal__label--second label" for="date_start">@lang('site.date_start')</label>
                                <input class="personal__input personal__input--second input" type="date" id="date_start" name="date_start"
                                       placeholder="@lang('site.date_format')" value="{{ old('date_start') ?? '' }}" required>
                            </div>

                            <div class="experience__unit">
                                <label class="personal__label personal__label--second label" for="current"><input class="checkbox__input input" type="checkbox" id="current" name="current">@lang('site.current_time')</label>

                            </div>

                            <div class="experience__unit" id="experienceEndDate">
                                <label class="personal__label personal__label--second label" for="date_end">@lang('site.date_end')</label>
                                <input class="personal__input personal__input--second input" type="date" id="date_end" name="date_end"
                                       placeholder="@lang('site.date_format')" value="{{ old('date_end') ?? '' }}" >
                            </div>

                            <div class="experience__unit">
                                <label class="personal__label label" for="company">@lang('site.company')</label>
                                <input class="personal__input personal__input--second input" type="text" id="company" name="company{{ $prefix }}"
                                       placeholder="@lang('site.company')" value="{{ old('company' . $prefix) ?? '' }}" required>
                            </div>
                            <div class="experience__unit">
                                <label class="personal__label label" for="position">@lang('site.position')</label>
                                <input class="personal__input personal__input--second input" type="text" id="position" name="position{{ $prefix }}"
                                       placeholder="@lang('site.position')" value="{{ old('position' . $prefix) ?? '' }}" required>
                            </div>
                            <div class="experience__unit">
                                <div class="textarea-div">
                                    <label class="skill__label label" for="duties">@lang('site.duties')</label>
                                    <textarea cols="2" rows="10" class="skill__input input" name="duties{{ $prefix }}" id="duties"></textarea>
                                </div>
                            </div>
                        </section>
                        <section class="registration">
                            <button type="submit" class="add__btn button">@lang('site.add')</button>
                            <button type="button" class="cancel__btn button" data-dismiss="modal">@lang('site.cancel')</button>
                        </section>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
