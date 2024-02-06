<?php

namespace App\Domain\Client\Enums;


use App\Base\Enums\StatusEnum;

/**
 * This is the enum class for table "clients".
 * Class App\Domain\Client\Enums\ClientStatusEnum
 *
 * @package  App\Domain\Client\Enums
 */
class ClientStatusEnum extends StatusEnum
{
    public const VERIFIED = 'verified';
    public const ACTIVE   = 'active';
    public const INACTIVE = 'inactive';
    public const DELETED  = 'deleted';

    public static function keys(): array
    {
        return [
            self::VERIFIED,
            self::ACTIVE,
            self::INACTIVE,
            self::DELETED,
        ];
    }
}
