<?php

namespace App\Domain\Client\Enums;


use App\Base\Enums\StatusEnum;

/**
 * This is the enum class for table "sports_categories".
 * Class App\Domain\Client\Enums\SportsCategoryStatusEnum
 *
 * @package  App\Domain\Client\Enums
*/

class SportsCategoryStatusEnum extends StatusEnum
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
