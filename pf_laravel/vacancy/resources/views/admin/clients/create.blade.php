@extends('admin.layouts.app', ['title' => ''])


@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">{{ __('admin.menu.client') }}</a></li>
    <li class="breadcrumb-item active">{{ __('admin.actions.creating') }}</li>
@endpush

@section('content')
    {!! BsForm::post(['route' => 'admin.clients.store', 'files' => true]) !!}
    @include('admin.clients.form')
    {!! BsForm::close() !!}
@endsection
