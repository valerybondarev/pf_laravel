<?php
/**
 * @var  \App\Domain\Mailing\Entities\Mailing $mailing
 *
 */

?>
@extends('admin.layouts.app', ['title' => __('admin.menu.mailing')])
@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.mailings.index') }}">{{ __('admin.menu.mailing') }}</a></li>
    <li class="breadcrumb-item active">{{ $mailing->title }}</li>
@endpush
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8 card-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="pt-4">
                        <h5 class="h3">
                            <label>{{ __('admin.columns.title') }}</label>
                            <span class="d-block">{{ $mailing->title }}</span>
                        </h5>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.text') }}</label><br>
                            <span>
                                {{$mailing->text}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.working') }}</label><br>
                            <span>
                                {{$mailing->working}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.sendAt') }}</label><br>
                            <span>
                                {{  $mailing->send_at ? date('Y-m-d', strtotime($mailing->send_at)) : null}}
                            </span>
                        </div>

                    </div>
                    <div class="pt-6 text-right">
                        {{ Html::link(route('admin.mailings.edit', ['mailing' => $mailing]), __('admin.actions.update'), ['class' => 'btn btn-primary']) }}
                        {{ BsForm::open(['url' => route('admin.mailings.destroy', ['mailing' => $mailing]), 'method' => 'delete', 'style' => 'display: inline-block']) }}
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