@extends('admin.layouts.app', ['title' => $vacancy->title])

@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.vacancies.index') }}">{{ __('admin.menu.vacancy') }}</a></li>
    <li class="breadcrumb-item active">{{ __('admin.actions.updating') }}</li>
@endpush

@section('content')
    {!! BsForm::put(['route' => ['admin.vacancies.update', 'vacancy'=> $vacancy], 'files' => true]) !!}
    @include('admin.vacancies.form')
    {!! BsForm::close() !!}
@endsection