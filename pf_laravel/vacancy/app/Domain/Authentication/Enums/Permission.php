<?php

namespace App\Domain\Authentication\Enums;

use App\Base\Enums\BaseEnum;

class Permission extends BaseEnum
{
    public const SYSTEM   = 'system';
    public const ADMIN    = 'admin';

    public static function keys(): array
    {
        return [
            self::SYSTEM,
            self::ADMIN,
        ];
    }
}
