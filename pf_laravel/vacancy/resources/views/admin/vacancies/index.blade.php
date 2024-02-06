<?php
/**
 * @var \App\Base\DataProviders\EloquentDataProvider $dataProvider
 */
?>
@extends('admin.layouts.app', ['title' => __('admin.menu.vacancy')])
@push('breadcrumbs')
    <li class="breadcrumb-item active">{{ __('admin.menu.vacancy') }}</li>
@endpush


@section('content')
    <div class="row">
        <div class="col">
            <?php
            $gridView = [
                'dataProvider' => $dataProvider,
                'rowsFormAction' => route('admin.vacancies.create'),
                'title' => __('admin.menu.vacancy'),
                'useFilters' => true,
                'columnFields' => [
                    [
                        'label' => __('admin.columns.title'),
                        'attribute' => 'title',
                        'value' => function (\App\Domain\Vacancy\Entities\Vacancy $vacancy) {
                            return Str::words($vacancy->text, 7) ;
                        },
                    ],
                    [
                        'label'     => __('admin.columns.categoryId'),
                        'attribute' => 'category_id',
                        'value'     => function (\App\Domain\Vacancy\Entities\Vacancy $vacancy) {
                            return $vacancy->category?->title;
                        },
                        'filter' => [
                            'class' => \Itstructure\GridView\Filters\DropdownFilter::class,
                            'data' => $categories
                        ]
                    ],
                    [
                        'class' => \Itstructure\GridView\Columns\ActionColumn::class,
                        'actionTypes' => [
                            'view' => function (\App\Domain\Vacancy\Entities\Vacancy $vacancy) {
                                return route('admin.vacancies.show', ['vacancy' => $vacancy]);
                            },
                            'edit' => function (\App\Domain\Vacancy\Entities\Vacancy $vacancy) {
                                return route('admin.vacancies.edit', ['vacancy' => $vacancy]);
                            },
                            'delete' => function (\App\Domain\Vacancy\Entities\Vacancy $vacancy) {
                                return route('admin.vacancies.destroy', ['vacancy' => $vacancy]);
                            },
                        ]
                    ]
                ]
            ];
            ?>
            @gridView($gridView)
        </div>
    </div>
@endsection
