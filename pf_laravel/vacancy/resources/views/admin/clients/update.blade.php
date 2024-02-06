@extends('admin.layouts.app', ['title' => $client->title])

@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">{{ __('admin.menu.client') }}</a></li>
    <li class="breadcrumb-item active">{{ __('admin.actions.updating') }}</li>
@endpush

@section('content')
    {!! BsForm::put(['route' => ['admin.clients.update', 'client'=> $client], 'files' => true]) !!}
    @include('admin.clients.form')
    {!! BsForm::close() !!}
@endsection