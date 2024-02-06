@extends('admin.layouts.app', ['title' => ''])


@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.mailings.index') }}">{{ __('admin.menu.mailing') }}</a></li>
    <li class="breadcrumb-item active">{{ __('admin.actions.creating') }}</li>
@endpush

@section('content')
    {!! BsForm::post(['route' => 'admin.mailings.store', 'files' => true, 'id' => 'mailing-form']) !!}
    @include('admin.mailings.form')
    {!! BsForm::close() !!}
@endsection
