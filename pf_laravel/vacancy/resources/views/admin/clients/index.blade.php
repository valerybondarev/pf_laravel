@extends('admin.layouts.app', ['title' => __('admin.menu.client')])
@push('breadcrumbs')
    <li class="breadcrumb-item active">{{ __('admin.menu.client') }}</li>
@endpush


@section('content')
    <div class="row">
        <div class="col">
            <?php
            $gridView = [
                'dataProvider' => $dataProvider,
                'rowsFormAction' => route('admin.clients.create'),
                'title' => __('admin.menu.client'),
                'useFilters' => true,
                'columnFields' => [
                    [
                        'label' => __('admin.columns.first_name'),
                        'attribute' => 'first_name',
                        'value' => function (\App\Domain\Client\Entities\Client $client) {
                            return mb_substr($client->first_name, 0, 200);
                        },
                    ],
                    [
                        'label' => __('admin.columns.lastName'),
                        'attribute' => 'last_name',
                        'value' => function (\App\Domain\Client\Entities\Client $client) {
                            return mb_substr($client->last_name, 0, 200);
                        },
                    ],
                    [
                        'label' => __('admin.columns.middleName'),
                        'attribute' => 'middle_name',
                        'value' => function (\App\Domain\Client\Entities\Client $client) {
                            return mb_substr($client->middle_name, 0, 200);
                        },
                    ],
                    [
                        'label' => __('admin.columns.bornAt'),
                        'attribute' => 'born_at',
                        'value' => function (\App\Domain\Client\Entities\Client $client) {
                            return $client->born_at?->format('d.m.Y');
                        },
                    ],
                    [
                        'label' => __('admin.columns.role'),
                        'attribute' => 'role',
                        'value' => function (App\Domain\Client\Entities\Client $clients) {
                            return App\Domain\Client\Enums\ClientRoleEnum::label($clients->role);
                        },
                        'filter' => [
                            'class' => Itstructure\GridView\Filters\DropdownFilter::class,
                            'data' => $roles
                        ],
                    ],
                    [
                        'label' => __('admin.columns.status'),
                        'attribute' => 'status',
                        'value' => function (App\Domain\Client\Entities\Client $clients) {
                            return App\Domain\Client\Enums\ClientStatusEnum::label($clients->status);
                        },
                        'filter' => [
                            'class' => Itstructure\GridView\Filters\DropdownFilter::class,
                            'data' => $statuses
                        ],
                    ],
                    [
                        'class' => \Itstructure\GridView\Columns\ActionColumn::class,
                        'actionTypes' => [
                            'view' => function (\App\Domain\Client\Entities\Client $client) {
                                return route('admin.clients.show', ['client' => $client]);
                            },
                            'edit' => function (\App\Domain\Client\Entities\Client $client) {
                                return route('admin.clients.edit', ['client' => $client]);
                            },
                            'delete' => function (\App\Domain\Client\Entities\Client $client) {
                                return route('admin.clients.destroy', ['client' => $client]);
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
