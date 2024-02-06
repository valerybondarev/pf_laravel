@extends('admin.layouts.app', ['title' => __('admin.menu.users')])

@push('breadcrumbs')
	<li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">{{ __('admin.menu.users') }}</a></li>
	<li class="breadcrumb-item">{{ Html::link(route('admin.users.show', ['user' => $user]), $user->getFullName()) }}</li>
	<li class="breadcrumb-item active">{{ __('admin.actions.updating') }}</li>
@endpush

@section('content')
	{!! BsForm::put(['route' => ['admin.users.update', 'user' => $user], 'files' => true]) !!}
	@include('admin.users.form')
	{!! BsForm::close() !!}
@endsection