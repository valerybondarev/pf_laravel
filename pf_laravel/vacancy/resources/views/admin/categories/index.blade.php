@extends('admin.layouts.app', ['title' => __('admin.menu.category')])

@push('breadcrumbs')
    <li class="breadcrumb-item active">{{ __('admin.menu.category') }}</li>
@endpush

@section('content')
    <div class="row">
        <div class="col">
            @gridView([
                'dataProvider' => $dataProvider,
                'rowsFormAction' => route('admin.categories.create'),
                'title' => __('admin.menu.product'),
                'useFilters' => true,
                'columnFields' => [
                    [
                        'label' => __('admin.columns.title'),
                        'attribute' => 'title',
                        'value' => function (\App\Domain\Catalog\Entities\Category $category) {
                            return mb_substr($category->title, 0, 20);
                        },
                    ],
                    [
                        'label' => __('admin.columns.status'),
                        'attribute' => 'status',
                        'value' => function (\App\Domain\Catalog\Entities\Category $category) {
                            return $category->getStatus();
                        },
                        'format' => 'html',
                        'htmlAttributes' => ['class' => 'sort']
                    ],
                    [
                        'label' => __('admin.columns.created_at'),
                        'attribute' => 'alias',
                        'value' => function (\App\Domain\Catalog\Entities\Category $category) {
                            return $category->getCreatedAt();
                        },
                        'format' => 'html',
                        'htmlAttributes' => ['class' => 'sort']
                    ],
                    [
                        'class' => \Itstructure\GridView\Columns\ActionColumn::class,
                        'actionTypes' => [
                            'view' => function (\App\Domain\Catalog\Entities\Category $category) {
                                return route('admin.categories.show', ['category' => $category]);
                            },
                            'edit' => function (\App\Domain\Catalog\Entities\Category $category) {
                            return route('admin.categories.edit', ['category' => $category]);
                            },
                            'delete' => function (\App\Domain\Catalog\Entities\Category $category) {
                            return route('admin.categories.destroy', ['category' => $category]);
                            },
                        ]
                    ]
                ]
            ])
        </div>
    </div>
@endsection

