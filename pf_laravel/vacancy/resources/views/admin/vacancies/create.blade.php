@extends('admin.layouts.app', ['title' => ''])


@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.vacancies.index') }}">{{ __('admin.menu.vacancy') }}</a></li>
    <li class="breadcrumb-item active">{{ __('admin.actions.creating') }}</li>
@endpush

@section('content')
    {!! BsForm::post(['route' => 'admin.vacancies.store', 'files' => true]) !!}
    @include('admin.vacancies.form')
    {!! BsForm::close() !!}
@endsection
