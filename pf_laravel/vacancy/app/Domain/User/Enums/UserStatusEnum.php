<?php

namespace App\Domain\User\Enums;

use App\Base\Enums\BaseEnum;

class UserStatusEnum extends BaseEnum
{
    public const ACTIVE   = 'active';
    public const INACTIVE = 'inactive';
    public const BLOCKED  = 'blocked';

    public static function keys(): array
    {
        return [
            self::ACTIVE,
            self::INACTIVE,
            self::BLOCKED,
        ];
    }
}