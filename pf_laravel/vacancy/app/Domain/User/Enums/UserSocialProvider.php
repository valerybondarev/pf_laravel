<?php

namespace App\Domain\User\Enums;

use App\Base\Enums\BaseEnum;

class UserSocialProvider extends BaseEnum
{
    public const FACEBOOK = 'facebook';
    public const GOOGLE   = 'google';

    public static function keys(): array
    {
        return [
            self::FACEBOOK,
            self::GOOGLE,
        ];
    }
}
