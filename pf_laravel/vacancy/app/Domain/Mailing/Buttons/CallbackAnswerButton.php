<?php

namespace App\Domain\Mailing\Buttons;

use App\Bot\Entities\Keyboard;
use App\Bot\Enums\ButtonTypeEnum;
use App\Bot\Store\UserState;
use App\Bot\Traits\MakesKeyboard;
use App\Domain\Mailing\Entities\MailingButton;

/**
 * Description Кнопка показа быстрого сообщения
 */
class CallbackAnswerButton extends BaseButton
{
    public string $type  = ButtonTypeEnum::INLINE;
    public array  $json  = [
        'text' => null,
    ];
    public array  $rules = [
        'text' => 'required|string|max:200',
    ];

    public function handle(UserState $userState, MailingButton $button)
    {
        $text = $button->getJson()['text'];
        $userState->answerCallbackQuery($text);
        $userState->bot->editMessageReplyMarkup(
            $userState->user->telegram_id,
            $userState->message->message->message_id,
            null,
            ['inline_keyboard' => [], 'resize_keyboard' => true]
        );
    }
}