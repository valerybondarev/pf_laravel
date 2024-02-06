<?php

namespace App\Domain\Mailing\Enums;

use App\Base\Enums\StatusEnum;
use App\Domain\Mailing\Buttons\ButtonInterface;
use App\Domain\Mailing\Buttons\CallbackAnswerButton;
use App\Domain\Mailing\Buttons\SubscribeClientListButton;

class MailingButtonListEnum extends StatusEnum
{
    const SUBSCRIBE_CLIENT_LIST  = 'subscribeClientList';
    const CALLBACK_ANSWER_BUTTON = 'callbackAnswerButton';

    public static function keys(): array
    {
        return [
            self::SUBSCRIBE_CLIENT_LIST,
            self::CALLBACK_ANSWER_BUTTON,
        ];
    }

    public static function relations(): array
    {
        return [
            self::SUBSCRIBE_CLIENT_LIST  => SubscribeClientListButton::class,
            self::CALLBACK_ANSWER_BUTTON => CallbackAnswerButton::class,
        ];
    }

    /**
     * @param $action
     *
     * @return array|ButtonInterface
     */
    public static function relationByAction($action): ButtonInterface|array
    {
        return match ($action) {
            self::SUBSCRIBE_CLIENT_LIST  => app(SubscribeClientListButton::class),
            self::CALLBACK_ANSWER_BUTTON => app(CallbackAnswerButton::class),
        };
    }

    public static function labels(): array
    {
        return collect(static::keys())
            ->flip()
            ->mapWithKeys(function ($value, $key) {
                return [$key => __("admin.mailingButtonList.$key")];
            })
            ->all();
    }
}