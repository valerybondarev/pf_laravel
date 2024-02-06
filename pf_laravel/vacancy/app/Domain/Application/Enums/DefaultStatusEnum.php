<?php

namespace App\Domain\Application\Enums;

use App\Base\Enums\StatusEnum;

class DefaultStatusEnum extends StatusEnum
{
    public static function keys(): array
    {
        return [
            self::ACTIVE,
            self::INACTIVE,
            self::DELETED,
        ];
    }
}
