<?php
/**
 * @var \App\Base\DataProviders\EloquentDataProvider $dataProvider
 */

?>
@extends('admin.layouts.app', ['title' => __('admin.menu.mailing')])
@push('breadcrumbs')
    <li class="breadcrumb-item active">{{ __('admin.menu.mailing') }}</li>
@endpush


@section('content')
    <div class="row">
        <div class="col">
            <?php
            $gridView = [
                'dataProvider'   => $dataProvider,
                'rowsFormAction' => route('admin.mailings.create'),
                'title'          => __('admin.menu.mailing'),
                'useFilters'     => true,
                'columnFields'   => [
                    [
                        'label'     => __('admin.columns.title'),
                        'attribute' => 'title',
                        'value'     => function (\App\Domain\Mailing\Entities\Mailing $mailing) {
                            return mb_substr($mailing->title, 0, 200);
                        },
                    ],
                    [
                        'label'     => __('admin.columns.status'),
                        'attribute' => 'status',
                        'value'     => function (App\Domain\Mailing\Entities\Mailing $mailings) {
                            return App\Domain\Mailing\Enums\MailingStatusEnum::label($mailings->status);
                        },
                    ],
                    [
                        'label'     => __('admin.columns.sendAt'),
                        'attribute' => 'send_at',
                        'value'     => function (App\Domain\Mailing\Entities\Mailing $mailings) {
                            return $mailings->send_at->format('d.m.Y H:i');
                        },
                    ],
                    [
                        'label' => 'Всего отправлений/Не отправлено',
                        'value' => function (App\Domain\Mailing\Entities\Mailing $mailings) {
                            return $mailings->mailingResults->count() . '/'
                                   . $mailings->mailingResults->filter(fn(\App\Domain\Mailing\Entities\MailingResult $mailingResult) => $mailingResult->error)
                                       ->count();
                        },
                    ],
                    [
                        'class'       => \Itstructure\GridView\Columns\ActionColumn::class,
                        'actionTypes' => [
                            'view'   => function (\App\Domain\Mailing\Entities\Mailing $mailing) {
                                return route('admin.mailings.show', ['mailing' => $mailing]);
                            },
                            'edit'   => function (\App\Domain\Mailing\Entities\Mailing $mailing) {
                                return route('admin.mailings.edit', ['mailing' => $mailing]);
                            },
                            'delete' => function (\App\Domain\Mailing\Entities\Mailing $mailing) {
                                return route('admin.mailings.destroy', ['mailing' => $mailing]);
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
