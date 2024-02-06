<?php
/**
 * @var \App\Domain\Mailing\Entities\MailingButton $mailingButton
 */
?>
<div class="col-lg-12">
    <div class="row mailing-button">
        {{ Form::hidden("buttons[$index][action]", $mailingButton->action ?? $mailingButton['action']) }}
        <div class="col-lg-4">
            {{ BsForm::text("buttons[$index][title]")
                    ->value(old("buttons[$index][title]", $mailingButton->title ?? $mailingButton['title']))
                    ->placeholder(__('admin.columns.title'))
                    ->label(__('admin.columns.title'))
            }}
        </div>
        <div class="col-lg-2">
            {{ BsForm::text("buttons[$index][sort]")
                    ->value(old("buttons[$index][sort]", ($mailingButton->sort ?? $mailingButton['sort']) ?: 100))
                    ->placeholder(__('admin.columns.sort'))
                    ->label(__('admin.columns.sort'))
            }}
        </div>
        <div class="col-lg-3 align-self-center">
            {{ BsForm::select("buttons[$index][json][clientListId]", $clientLists)
                    ->value(old("buttons[$index][json][clientListId]", $mailingButton['json']['clientListId'] ?? $mailingButton->getJson()['clientListId']))
                    ->placeholder(__('admin.columns.clientListId'))
                    ->label(__('admin.columns.clientListId'))
            }}
        </div>
        <div class="col-lg-1 align-self-center">
            {{ Form::button('<i class="fa fa-times text-white"></i>', ['class' => 'btn-xs btn-danger mailing-button-remove']) }}
        </div>
    </div>
</div>