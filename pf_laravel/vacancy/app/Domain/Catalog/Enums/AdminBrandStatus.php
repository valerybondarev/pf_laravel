<?php

namespace App\Domain\Catalog\Enums;

use App\Base\Enums\StatusEnum;

class AdminBrandStatus extends StatusEnum
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
