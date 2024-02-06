<?php

/**
 * @var  \App\Domain\Client\Entities\Client      $client
 * @var \App\Domain\TourClub\Entities\TourClub[] $tourClubs
 *
 */

?>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="row mb-3">
            <div class="col-md-4">
                {{ BsForm::text('lastName')
                        ->value(old('lastName', $client->last_name))
                        ->placeholder(__('admin.columns.lastName'))
                        ->label(__('admin.columns.lastName'))
                }}
            </div>
            <div class="col-md-4">
                {{ BsForm::text('firstName')
                        ->value(old('firstName', $client->first_name))
                        ->placeholder(__('admin.columns.firstName'))
                        ->label(__('admin.columns.firstName'))
                }}
            </div>
            <div class="col-md-4">
                {{ BsForm::text('middleName')
                        ->value(old('middleName', $client->middle_name))
                        ->placeholder(__('admin.columns.middleName'))
                        ->label(__('admin.columns.middleName'))
                }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                {{ BsForm::select('tourClubId', $tourClubs->keyBy('id')->map->title)
                        ->value(old('tourClubId', $client->tour_club_id))
                        ->label(__('admin.columns.tourClubId'))
                }}
            </div>
            <div class="col-md-4">
                {{ BsForm::select('sportsCategoryId', $sportCategories->keyBy('id')->map->title)
                        ->value(old('sportsCategoryId', $client->sports_category_id))
                        ->placeholder(__('admin.columns.sports_category_id'))
                        ->label(__('admin.columns.sports_category_id'))
                }}
            </div>
            <div class="col-md-4">
                {{ BsForm::text('command')
                        ->value(old('command', $client->command))
                        ->placeholder(__('admin.columns.command'))
                        ->label(__('admin.columns.command'))
                        ->attribute('disabled', 'disabled')
                }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                {{ BsForm::number('telegramId')
                        ->value(old('telegramId', $client->telegram_id))
                        ->placeholder(__('admin.columns.telegramId'))
                        ->label(__('admin.columns.telegramId'))
                }}
            </div>
            <div class="col-md-4">
                {{ BsForm::text('username')
                        ->value(old('username', $client->username))
                        ->placeholder(__('admin.columns.username'))
                        ->label(__('admin.columns.username'))
                }}
            </div>
            <div class="col-md-4">
                {{ BsForm::text('vkLink')
                        ->value(old('vkLink', $client->vkLink))
                        ->placeholder(__('admin.columns.vkLink'))
                        ->label(__('admin.columns.vkLink'))
                }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                {{ BsForm::text('phone')
                        ->value(old('phone', $client->phone))
                        ->placeholder(__('admin.columns.phone'))
                        ->label(__('admin.columns.phone'))
                }}
            </div>

            <div class="col-md-4">
                {{ BsForm::text('bornAt')
                        ->value(old('bornAt', $client->born_at?->format('Y-m-d')))
                        ->placeholder(__('admin.columns.bornAt'))
                        ->label(__('admin.columns.bornAt') . "({$client->born_at?->format('d.m.Y')})")
                }}
            </div>

            <div class="col-md-4">
                {{ BsForm::text('yearInTk')
                ->value(old('yearInTk', $client->year_in_tk))
                ->placeholder(__('admin.columns.yearInTk'))
                ->label(__('admin.columns.yearInTk'))
        }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-2">
                {{ BsForm::checkbox('statusLearn')
                        ->value(1)
                        ->checked(old('statusLearn', $client->status_learn))
                        ->label(__('admin.columns.statusLearn'))
                        ->wrapperAttribute('class', 'custom-control custom-control-alternative custom-checkbox')
                }}
                {{ BsForm::checkbox('liveInDorm')
                        ->value(1)
                        ->checked(old('liveInDorm', $client->live_in_dorm))
                        ->label(__('admin.columns.liveInDorm'))
                        ->wrapperAttribute('class', 'custom-control custom-control-alternative custom-checkbox')
                }}
            </div>
            <div class="col-md-3">
                {{ BsForm::number('yearInUniversity')
                        ->value(old('yearInUniversity', $client->year_in_university))
                        ->placeholder(__('admin.columns.yearInUniversity'))
                        ->label(__('admin.columns.yearInUniversity'))
                }}
            </div>
            <div class="col-md-3">
                {{ BsForm::text('department')
                        ->value(old('department', $client->department))
                        ->placeholder(__('admin.columns.department'))
                        ->label(__('admin.columns.department'))
                }}
            </div>
            <div class="col-md-3">
                {{ BsForm::text('group')
                        ->value(old('group', $client->group))
                        ->placeholder(__('admin.columns.group'))
                        ->label(__('admin.columns.group'))
                }}
            </div>
        </div>
        {{ BsForm::text('workOrganization')
                ->value(old('workOrganization', $client->work_organization))
                ->placeholder(__('admin.columns.workOrganization'))
                ->label(__('admin.columns.workOrganization'))
        }}

        {{ BsForm::text('campingExperience')
                ->value(old('campingExperience', $client->camping_experience))
                ->placeholder(__('admin.columns.campingExperience'))
                ->label(__('admin.columns.campingExperience'))
        }}
        <div class="row mb-3">
            <div class="col-md-4">
                {{ BsForm::select('status', \App\Domain\Client\Enums\ClientStatusEnum::labels())
                        ->value(old('status', $client->status) ?: \App\Domain\Client\Enums\ClientStatusEnum::ACTIVE)
                        ->placeholder(__('admin.columns.status'))
                        ->label(__('admin.columns.status'))
                }}
            </div>
            <div class="col-md-4">
                {{ BsForm::select('role', \App\Domain\Client\Enums\ClientRoleEnum::labels())
                        ->value(old('role', $client->role) ?: \App\Domain\Client\Enums\ClientRoleEnum::USER)
                        ->placeholder(__('admin.columns.role'))
                        ->label(__('admin.columns.role'))
                }}
            </div>
            <div class="col-md-4">
                {{ BsForm::text('registeredAt')
                        ->value(old('registeredAt', $client->registered_at?->format('d.m.Y G:i')))
                        ->placeholder(__('admin.columns.registeredAt'))
                        ->label(__('admin.columns.registeredAt'))
                        ->attribute('datetime-picker', 1)
                        ->wrapperAttribute('class', 'form-group date')
                }}
            </div>
        </div>
        {{ BsForm::number('points')
                ->value(old('points', $client->points))
                ->placeholder(__('admin.columns.points'))
                ->label(__('admin.columns.points'))
        }}

        {{ BsForm::checkbox('mailingNews')
                ->value(1)
                ->checked(old('mailingNews', $client->mailing_news))
                ->label(__('admin.columns.mailingNews'))
                ->wrapperAttribute('class', 'custom-control custom-control-alternative custom-checkbox')
        }}
        {{ BsForm::checkbox('mailingEvents')
                ->value(1)
                ->checked(old('mailingEvents', $client->mailing_events))
                ->label(__('admin.columns.mailingEvents'))
                ->wrapperAttribute('class', 'custom-control custom-control-alternative custom-checkbox')
        }}
        {{ BsForm::checkbox('start')
                ->value(1)
                ->checked(old('start', $client->start))
                ->label(__('admin.columns.start'))
                ->wrapperAttribute('class', 'custom-control custom-control-alternative custom-checkbox')
        }}
    </div>
</div>
<div class="col-md-12 text-right mt-5">
    {{ BsForm::submit(__('admin.actions.submit'))->primary() }}
</div>
@include('admin.layouts.modals.cropper')
