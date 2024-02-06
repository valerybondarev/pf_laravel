<?php

namespace App\Domain\Client\Enums;


use App\Base\Enums\StatusEnum;

/**
 * This is the enum class for table "client_lists".
 * Class App\Domain\Client\Enums\ClientListStatusEnum
 *
 * @package  App\Domain\Client\Enums
*/

class ClientListStatusEnum extends StatusEnum
{
    public const ACTIVE = 'active';
    public const INACTIVE = 'inactive';
    public const DELETED = 'deleted';

    public static function keys(): array
    {
        return [
            self::ACTIVE,
            self::INACTIVE,
            self::DELETED,
        ];
    }
}
