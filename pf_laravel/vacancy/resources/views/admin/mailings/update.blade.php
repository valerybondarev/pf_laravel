@extends('admin.layouts.app', ['title' => $mailing->title])

@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.mailings.index') }}">{{ __('admin.menu.mailing') }}</a></li>
    <li class="breadcrumb-item active">{{ __('admin.actions.updating') }}</li>
@endpush

@section('content')
    {!! BsForm::put(['route' => ['admin.mailings.update', 'mailing'=> $mailing], 'files' => true, 'id' => 'mailing-form']) !!}
    @include('admin.mailings.form')
    {!! BsForm::close() !!}
@endsection