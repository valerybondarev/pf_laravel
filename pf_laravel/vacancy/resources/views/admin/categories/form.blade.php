<?php
/**
 * @var \App\Domain\Catalog\Entities\Category $category
 *
 */
?>

<div class="row justify-content-center">
    <div class="col-lg-6 card-wrapper">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">{{ __('admin.sections.common') }}</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        {{ BsForm::text('title')
                                ->value(old('title', $category->title))
                                ->placeholder(__('admin.columns.title'))
                        }}
                        {{--{{ BsForm::text('alias')
                                ->value(old('alias', $category->alias))
                                ->placeholder(__('admin.columns.alias'))
                        }}--}}
                        <div class="col-lg-12">
                            <h6 class="heading-small text-muted mb-4">Теги</h6>
                            <div class="pl-lg-4">
                                {{ BsForm::select('keyWords[]', $category->keyWords->map->title->flip()->toArray())
                                        ->multiple()
                                        ->placeholder('Введите тег')
                                        ->attribute(['data-toggle' => 'tags'])
                                }}
                            </div>
                        </div>
                        {{ BsForm::select('status', \App\Domain\Application\Enums\DefaultStatusEnum::labels())
                                ->value(old('status', $category->status) ?: \App\Domain\Application\Enums\DefaultStatusEnum::ACTIVE)
                                ->placeholder(__('admin.columns.status'))
                        }}
                        {{--<div class="col-lg-12">
                            <h6 class="heading-small text-muted mb-4">Изображение</h6>
                            <div class="pl-lg-4">
                                @php
                                    $preview = $category->file->url ?? null;
                                    if (($oldPreview = old('image', -1)) && $oldPreview !== -1) {
                                        $preview = find_image($oldPreview)->url ?? null;
                                    }
                                @endphp
                                {{ BsForm::text('image')
                                    ->value(old('image', $category->image))
                                    ->placeholder(__('admin.columns.image'))
                                    ->attribute(['single-image-cropper'=> true, 'hidden' => true, 'preview' => $preview])
                                    ->wrapperAttribute(['class' => 'user-avatar'])
                                }}
                            </div>
                        </div>--}}
                    </div>
                    <div class="col-md-12 text-right">
                        {{ BsForm::submit(__('admin.actions.submit')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script src="{{ asset('admin/vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
@endpush
@include('admin.layouts.modals.cropper')
