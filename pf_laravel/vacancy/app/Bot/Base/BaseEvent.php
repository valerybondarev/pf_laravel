<?php

namespace App\Bot\Base;


use App\Bot\Store\UserState;

abstract class BaseEvent
{
    public static function make(UserState $userState): static
    {
        return app(static::class, ['userState' => $userState]);
    }
}
