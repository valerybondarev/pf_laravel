@extends('admin.layouts.app', ['title' => __('admin.menu.demo')])

@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.menu.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('admin.menu.demo') }}</li>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8 card-wrapper">
            <div class="card deputy-form">
                <div class="card-body">
                    <div class="col-lg-12">
                        <h6 class="heading-small text-muted mb-4">Поля</h6>
                        <div class="pl-lg-4">
                            {{ BsForm::text('text')
                                    ->label('Текст')
                                    ->placeholder('Текст')
                            }}

                            {{ BsForm::select('list', null, [1, 2 ,3])
                                    ->label('Список')
                                    ->placeholder('Список')
                                    ->attribute(['data-toggle' => 'select'])
                            }}

                            {{ BsForm::select('list[]', null, [1, 2 ,3])
                                    ->multiple()
                                    ->label('Множественный список')
                                    ->placeholder('Множественный список')
                                    ->attribute(['data-toggle' => 'select'])
                            }}

                        </div>
                    </div>
                    <div class="col-lg-12">
                        <h6 class="heading-small text-muted mb-4">Изображение</h6>
                        <div class="pl-lg-4">
                            {{ BsForm::text('image')
                                ->placeholder(__('admin.columns.image'))
                                ->attribute(['single-image-cropper'=> true, 'hidden' => true])
                                ->wrapperAttribute(['class' => 'user-avatar'])
                            }}
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <h6 class="heading-small text-muted mb-4">Изображение с пропорциями</h6>
                        <div class="pl-lg-4">
                            {{ BsForm::text('image')
                                ->placeholder(__('admin.columns.image'))
                                ->attribute(['single-image-cropper'=> true, 'ratio' => 1, 'hidden' => true])
                                ->wrapperAttribute(['class' => 'user-avatar'])
                            }}
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <h6 class="heading-small text-muted mb-4">Галерея</h6>
                        <div class="pl-lg-4">
                            <div class="mb-3 col-md-12 gallery" gallery="gallery[]">
                                <div class="gallery__upload" gallery-upload>
                                    <div class="gallery__upload__content">
                                        <span class="icon-lg"><i class="ni ni-cloud-upload-96"></i></span>
                                        <p>{{ __('admin.crop.upload') }}</p>
                                    </div>
                                </div>
                                <div class="gallery__images" gallery-images>
                                    @foreach([] as $index => $imageId)
                                        @if(($file = (new \App\Domain\Application\Repositories\FileRepository())->one($imageId)) && $file->exists)
                                            {{ BsForm::text('gallery[]')
                                                    ->value($imageId)
                                                    ->attribute(['preview' => $file->widen(300)->url, 'hidden' => true])
                                                    ->wrapperAttribute(['class' => 'crop__container', 'gallery-image-cropper-container' => true])
                                            }}
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <h6 class="heading-small text-muted mb-4">Блочный редактор</h6>
                        <div class="pl-lg-4">
                            {{ BsForm::text('text')
                                ->attribute(['editor' => true, 'hidden' => true])
                            }}
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <h6 class="heading-small text-muted mb-4">Теги</h6>
                        <div class="pl-lg-4">
                            {{ BsForm::select('tags[]')
                                    ->multiple()
                                    ->placeholder('Введите тег')
                                    ->attribute(['data-toggle' => 'tags'])
                            }}
                        </div>
                    </div>
                    <div class="col-lg-12 mb-5">
                        <h6 class="heading-small text-muted mb-4">Файлы</h6>
                        <div class="pl-lg-4">
                            {{ Form::file('documents[]', [
                                    'multiple' => true,
                                    'dropzone' => true,
                                    'hidden' => true,
                                    'data-files' => "[]"
                            ]) }}
                        </div>
                    </div>
                    <div class="col-lg-12 mb-5">
                        <h6 class="heading-small text-muted mb-4">Файл</h6>
                        <div class="pl-lg-4">
                            {{ Form::file('document', [
                                    'dropzone' => true,
                                    'hidden' => true,
                                    'data-files' => "[]"
                            ]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('admin.layouts.modals.cropper')

@push('js')
    <script src="{{ asset('admin/vendor/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
@endpush
