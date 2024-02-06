<?php

/**
 * @var  \App\Domain\Vacancy\Entities\Vacancy $vacancy
 *
*/

?>
<div class="row justify-content-center">
    <div class="col-lg-10 card-wrapper">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">{{ __('admin.sections.common') }}</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        {{ BsForm::select('categoryId', $categories)
                                ->value(old('categoryId', $vacancy->category_id))
                                ->label(__('admin.columns.categoryId'))
                        }}

                        {{ BsForm::textarea('text')
                                ->value(old('text', $vacancy->text))
                                ->placeholder(__('admin.columns.text'))
                                ->label(__('admin.columns.text'))
                        }}

                    </div>
                    <div class="col-md-12 text-right">
                        {{ BsForm::submit(__('admin.actions.submit')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.layouts.modals.cropper')
