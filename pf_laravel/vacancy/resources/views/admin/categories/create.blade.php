@extends('admin.layouts.app', ['title' => __('admin.menu.categories')])

@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">{{ __('admin.menu.categories') }}</a></li>
    <li class="breadcrumb-item active">{{ __('admin.actions.creating') }}</li>
@endpush

@section('content')
    {!! BsForm::post(['route' => 'admin.categories.store', 'files' => true]) !!}
    @include('admin.categories.form')
    {!! BsForm::close() !!}
@endsection
