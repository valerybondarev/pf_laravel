<?php

namespace App\Domain\Catalog\Enums;

use App\Base\Enums\StatusEnum;

class CategoryStatus extends StatusEnum
{
    public static function keys(): array
    {
        return [
            self::ACTIVE,
            self::DELETED,
        ];
    }
}
