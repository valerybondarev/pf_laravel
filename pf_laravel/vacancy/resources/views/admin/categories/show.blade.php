<?php
/**
 * @var \App\Domain\News\Entities\News $category
 *
 */
?>
@extends('admin.layouts.app', ['title' => __('admin.menu.category')])

@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">{{ __('admin.menu.category') }}</a></li>
    <li class="breadcrumb-item active">{{ $category->getTitle() }}</li>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-4 card-wrapper">
            <div class="card">
                <div class="card-body">

                    <div class="pt-4">
                        <h5 class="h3">
                            <label>{{ __('admin.columns.title') }}</label>
                            <span class="d-block">{{ $category->getTitle() }}</span>
                        </h5>
                        <div class="mt-3">
                            <label>{{ __('admin.columns.status') }}</label><br>
                            <span>
                                {{$category->getStatus()}}
                            </span>
                        </div>
                        <div class="mt-3">
                            <label>{{ __('admin.columns.image') }}</label><br>
                            <img src="{!! $category->file->url ?? 'noImage.jpg' !!}" width="400"/>
                        </div>
                    </div>
                    <div class="pt-6 text-right">
                        {{ Html::link(route('admin.categories.edit', ['category' => $category]), __('admin.actions.update'), ['class' => 'btn btn-primary']) }}
                        {{ BsForm::open(['url' => route('admin.categories.destroy', ['category' => $category]), 'method' => 'delete', 'style' => 'display: inline-block']) }}
                        <button class="btn btn-danger" data-toggle="tooltip" data-placement="top"
                                title="{{ __('admin.actions.destroy') }}">
                            {{ __('admin.actions.destroy') }}
                        </button>
                        {{ BsForm::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('admin.layouts.modals.cropper')
