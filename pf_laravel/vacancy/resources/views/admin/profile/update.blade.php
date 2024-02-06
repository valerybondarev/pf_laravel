@extends('admin.layouts.app', ['title' => __('admin.nav.profile')])

@push('breadcrumbs')
    <li class="breadcrumb-item active">{{ __('admin.nav.profile') }}</li>
    <li class="breadcrumb-item active">{{ __('admin.actions.updating') }}</li>
@endpush

@section('content')
    {!! BsForm::put(['route' => ['admin.profile.update'], 'files' => true]) !!}
    <div class="row justify-content-center">
        <div class="col-lg-6 card-wrapper">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">{{ $user->getFullName() }}</h3>
                </div>
                <div class="card-body">
                    <h6 class="heading-small text-muted mb-4">{{ __('admin.sections.common') }}</h6>
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                {{ BsForm::text('lastName')
                                    ->value(old('lastName', $user->last_name))
                                    ->placeholder(__('admin.columns.lastName'))
                                    ->label(__('admin.columns.lastName'))
                                }}
                                {{ BsForm::text('firstName')
                                    ->value(old('firstName', $user->first_name))
                                    ->placeholder(__('admin.columns.firstName'))
                                    ->label(__('admin.columns.firstName'))
                                }}

                                {{ BsForm::select('languageId', null, $languages)
                                    ->value(old('languageId', $user->language_id))
                                    ->placeholder(__('admin.columns.language'))
                                    ->label(__('admin.columns.language'))
                                    ->attribute('data-toggle', 'select')
                                }}
                            </div>
                            <div class="col-lg-6">
                                {{ BsForm::text('avatarId')
                                    ->value(old('avatarId', $user->avatar_id))
                                    ->placeholder(__('admin.columns.avatar'))
                                    ->label(__('admin.columns.avatar'))
                                    ->attribute(['single-image-cropper'=> true, 'preview' => find_image(old('avatarId', $user->avatar_id))->url ?? null, 'hidden' => true])
                                    ->wrapperAttribute(['class' => 'form-group user-avatar rounded-circle'])
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-right">
                        {{ BsForm::submit(__('admin.actions.submit')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! BsForm::close() !!}
@endsection

@include('admin.layouts.modals.cropper')
