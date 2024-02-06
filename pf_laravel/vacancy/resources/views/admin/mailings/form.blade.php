<?php

/**
 * @var  \App\Domain\Mailing\Entities\Mailing $mailing
 *
 */

?>
<div class="row justify-content-center">
    <div class="col-lg-10 card-wrapper">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">{{ __('admin.sections.common') }}</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        {{ BsForm::text('title')
                                ->value(old('title', $mailing->title))
                                ->placeholder(__('admin.columns.title'))
                                ->label(__('admin.columns.title'))
                        }}

                        {{ BsForm::textarea('text')
                                ->rows(4)
                                ->value(old('text', $mailing->text))
                                ->label(__('admin.columns.text'))
                                ->attribute('id', 'mailing-text')
                        }}
                        {{ BsForm::select('clientLists[]', $clientLists)
                                ->value(old('clientLists[]', $mailing->clientLists))
                                ->multiple()
                                ->attribute('data-toggle','select')
                                ->label(__('admin.menu.clientList'))
                        }}
                        {{ BsForm::text('sendAt', $mailing->send_at)
                                ->value(old('sendAt', $mailing->send_at?->format('d.m.Y H:i')) ?: \Carbon\Carbon::now('Europe/Moscow')->addHours()->format('d.m.Y H:i'))
                                ->placeholder(__('admin.columns.sendAt'))
                                ->label(__('admin.columns.sendAt'))
                                ->attribute('datetime-picker', 1)
                        }}

                        {{ BsForm::select('status')
                                ->options($statuses)
                                ->value(old('status', $mailing->status) ?: \App\Domain\Mailing\Enums\MailingStatusEnum::ACTIVE)
                                ->label(__('admin.columns.status'))
                        }}
                        <hr>
                        <h3>Тестовая отправка рассылки</h3>
                        <div id="mailing-container" class="container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    {{ BsForm::text('testUsername')
                                            ->value(433869407)
                                            ->label(__('admin.columns.sendUsername'))
                                    }}
                                </div>
                                <div class="col-md-6 align-self-center">
                                    <button id="send-test-mailing" class="btn btn-primary">Тестовая отправка</button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h3>Клавиатура</h3>
                        <div id="mailing-buttons-container" class="container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    {{ BsForm::select('buttonType', $buttonTypes)
                                            ->value(array_keys($buttonTypes)[0])
                                            ->label(__('admin.columns.selectButtonType'))
                                    }}
                                </div>
                                <div class="col-md-6 align-self-center">
                                    <button id="add-mailing-button" class="btn btn-primary">Добавить кнопку</button>
                                </div>
                            </div>
                            <div class="row mailing-button-list">
                                @foreach(old('buttons', $mailing->buttons) as $index => $mailingButton)
                                    @include('admin.mailings.blocks.' . $mailingButton?->action ?? $mailingButton['action'])
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-right">
                        {{ BsForm::submit(__('admin.actions.submit')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.layouts.modals.cropper')