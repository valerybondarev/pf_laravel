<?php
/**
 * @var  \App\Domain\Client\Entities\Client $client
 *
 */

?>
@extends('admin.layouts.app', ['title' => __('admin.menu.client')])
@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">{{ __('admin.menu.client') }}</a></li>
    <li class="breadcrumb-item active">{{ $client->title }}</li>
@endpush
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8 card-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="pt-4">
                        <div class="mt-3">
                            <label>{{ __('admin.columns.tour_club_id') }}</label><br>
                            <span>
                                {{$client->tour_club_id}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.sports_category_id') }}</label><br>
                            <span>
                                {{$client->sports_category_id}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.command') }}</label><br>
                            <span>
                                {{$client->command}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.telegram_id') }}</label><br>
                            <span>
                                {{$client->telegram_id}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.username') }}</label><br>
                            <span>
                                {{$client->username}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.first_name') }}</label><br>
                            <span>
                                {{$client->first_name}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.last_name') }}</label><br>
                            <span>
                                {{$client->last_name}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.middle_name') }}</label><br>
                            <span>
                                {{$client->middle_name}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.phone') }}</label><br>
                            <span>
                                {{$client->phone}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.year_in_tk') }}</label><br>
                            <span>
                                {{$client->year_in_tk}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.status_learn') }}</label><br>
                            <span>
                                {{$client->status_learn}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.year_in_university') }}</label><br>
                            <span>
                                {{$client->year_in_university}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.department') }}</label><br>
                            <span>
                                {{$client->department}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.group') }}</label><br>
                            <span>
                                {{$client->group}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.live_in_dorm') }}</label><br>
                            <span>
                                {{$client->live_in_dorm}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.work_organization') }}</label><br>
                            <span>
                                {{$client->work_organization}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.camping_experience') }}</label><br>
                            <span>
                                {{$client->camping_experience}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.status') }}</label><br>
                            <span>
                                {{$client->status}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.registeredAt') }}</label><br>
                            <span>
                                {{  $client->registered_at ? date('Y-m-d', strtotime($client->registered_at)) : null}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.points') }}</label><br>
                            <span>
                                {{$client->points}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.mailing_news') }}</label><br>
                            <span>
                                {{$client->mailing_news}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.mailing_events') }}</label><br>
                            <span>
                                {{$client->mailing_events}}
                            </span>
                        </div>

                        <div class="mt-3">
                            <label>{{ __('admin.columns.start') }}</label><br>
                            <span>
                                {{$client->start}}
                            </span>
                        </div>

                    </div>
                    <div class="pt-6 text-right">
                        {{ Html::link(route('admin.clients.edit', ['client' => $client]), __('admin.actions.update'), ['class' => 'btn btn-primary']) }}
                        {{ BsForm::open(['url' => route('admin.clients.destroy', ['client' => $client]), 'method' => 'delete', 'style' => 'display: inline-block']) }}
                        <button class="btn btn-danger" data-toggle="tooltip" data-placement="top"
                                title="{{ __('admin.actions.destroy') }}">
                                {{ __('admin.actions.destroy') }}
                        </button>
                            {{ BsForm::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('admin.layouts.modals.cropper')