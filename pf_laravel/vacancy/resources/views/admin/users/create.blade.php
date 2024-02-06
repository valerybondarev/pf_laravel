@extends('admin.layouts.app', ['title' => __('admin.menu.users')])

@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">{{ __('admin.menu.users') }}</a></li>
    <li class="breadcrumb-item active">{{ __('admin.actions.creating') }}</li>
@endpush

@section('content')
    {!! BsForm::post(['route' => 'admin.users.store', 'files' => true]) !!}
    @include('admin.users.form')
    {!! BsForm::close() !!}
@endsection
