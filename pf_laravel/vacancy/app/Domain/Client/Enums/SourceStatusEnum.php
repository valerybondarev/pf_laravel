<?php

namespace App\Domain\Client\Enums;


use App\Base\Enums\StatusEnum;

/**
 * This is the enum class for table "sources".
 * Class App\Domain\Client\Enums\SourceStatusEnum
 *
 * @package  App\Domain\Client\Enums
*/

class SourceStatusEnum extends StatusEnum
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
