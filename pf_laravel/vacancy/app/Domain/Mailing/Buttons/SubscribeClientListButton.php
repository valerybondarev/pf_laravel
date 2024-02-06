<?php

namespace App\Domain\Mailing\Buttons;

use App\Bot\Enums\ButtonTypeEnum;
use App\Bot\Store\UserState;
use App\Domain\Client\Repositories\ClientListRepository;
use App\Domain\Mailing\Entities\MailingButton;

/**
 * Description Кнопка подписки к какой-либо группе
 */
class SubscribeClientListButton extends BaseButton
{
    public string $type  = ButtonTypeEnum::INLINE;
    public array  $json  = [
        'clientListId' => null,
    ];
    public array  $rules = [
        'clientListId' => 'required|exists:client_lists,id',
    ];

    public function __construct(private ClientListRepository $clientListRepository)
    {
    }

    public function handle(UserState $userState, MailingButton $button)
    {
        $clientListId = $button->getJson()['clientListId'];
        $clientList = $this->clientListRepository->one($clientListId);
        $clientList->clients()->syncWithoutDetaching($userState->user->id);
        $userState->bot->editMessageReplyMarkup(
            $userState->user->telegram_id,
            $userState->message->message->message_id,
            null,
            ['inline_keyboard' => [], 'resize_keyboard' => true]
        );
        $userState->answerCallbackQuery('Это успех!');
    }
}