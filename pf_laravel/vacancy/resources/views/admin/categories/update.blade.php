@extends('admin.layouts.app', ['title' => __('admin.menu.category')])

@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">{{ __('admin.menu.category') }}</a></li>
    <li class="breadcrumb-item">{{ Html::link(route('admin.categories.show', ['category' => $category]), $category->getTitle()) }}</li>
    <li class="breadcrumb-item active">{{ __('admin.actions.updating') }}</li>
@endpush

@section('content')
    {!! BsForm::put(['route' => ['admin.categories.update', 'category'=> $category], 'files' => true]) !!}
    @include('admin.categories.form')
    {!! BsForm::close() !!}
@endsection
