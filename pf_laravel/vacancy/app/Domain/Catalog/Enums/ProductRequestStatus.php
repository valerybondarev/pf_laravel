<?php

namespace App\Domain\Catalog\Enums;

use App\Base\Enums\StatusEnum;

class ProductRequestStatus extends StatusEnum
{
    public const READ = 'read';
    public const UNREAD = 'unread';
    public static function keys(): array
    {
        return [
            self::READ,
            self::UNREAD,
            self::DELETED,
        ];
    }
}
