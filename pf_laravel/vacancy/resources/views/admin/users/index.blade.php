@extends('admin.layouts.app', ['title' => __('admin.menu.users')])

@push('breadcrumbs')
    <li class="breadcrumb-item active">{{ __('admin.menu.users') }}</li>
@endpush

@section('content')
    <div class="row">
        <div class="col">
            @gridView([
                'dataProvider' => $dataProvider,
                'rowsFormAction' => route('admin.users.create'),
                'title' => __('admin.menu.users'),
                'useFilters' => true,
                'columnFields' => [
                    [
                        'label' => __('admin.columns.avatar'),
                        'value' => function (\App\Domain\User\Entities\User $user) {
                            return $user->avatar->url ?? asset('admin/static/images/avatar.png');
                        },
                        'format' => [
                            'class' => \Itstructure\GridView\Formatters\ImageFormatter::class,
                            'htmlAttributes' => [
                                'class' => 'avatar rounded-circle'
                            ]
                        ],
                        'filter' => false,
                    ],
                    [
                        'label' => __('admin.columns.username'),
                        'attribute' => 'username',
                        'htmlAttributes' => ['class' => 'sort']
                    ],
                    [
                        'label' => __('admin.columns.email'),
                        'attribute' => 'email',
                        'value' => function (\App\Domain\User\Entities\User $user) {
                            return $user->email ? Html::link("mailto:$user->email", $user->email): null;
                        },
                        'format' => 'html',
                        'htmlAttributes' => ['class' => 'sort']
                    ],
                    [
                        'label' => __('admin.columns.lastName'),
                        'attribute' => 'last_name',
                        'htmlAttributes' => ['class' => 'sort']
                    ],
                    [
                        'label' => __('admin.columns.firstName'),
                        'attribute' => 'first_name',
                        'htmlAttributes' => ['class' => 'sort']
                    ],
                    [
                        'label' => __('admin.columns.phone'),
                        'attribute' => 'phone',
                        'value' => function (\App\Domain\User\Entities\User $user) {
                            return $user->phone ? Html::link("tel:" . $user->phone, $user->phone) : null;
                        },
                        'format' => 'html',
                        'htmlAttributes' => ['class' => 'sort']
                    ],
                    [
                        'label' => __('admin.columns.created_at'),
                        'attribute' => 'created_at',
                        'filter' => false,
                        'htmlAttributes' => ['class' => 'sort'],
                    ],
                    [
                        'class' => \Itstructure\GridView\Columns\ActionColumn::class,
                        'actionTypes' => [
                            'view' => function (\App\Domain\User\Entities\User $user) {
                                return route('admin.users.show', ['user' => $user]);
                            },
                            'edit' => function (\App\Domain\User\Entities\User $user) {
                                return route('admin.users.edit', ['user' => $user]);
                            },
                            'delete' => function (\App\Domain\User\Entities\User $user) {
                                return route('admin.users.destroy', ['user' => $user]);
                            },
                        ]
                    ]
                ]
            ])
        </div>
    </div>
@endsection

