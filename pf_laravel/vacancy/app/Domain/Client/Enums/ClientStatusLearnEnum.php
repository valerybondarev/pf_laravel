<?php

namespace App\Domain\Client\Enums;


use App\Base\Enums\StatusEnum;

/**
 * This is the enum class for table "clients".
 * Class App\Domain\Client\Enums\ClientStatusEnum
 *
 * @package  App\Domain\Client\Enums
*/

class ClientStatusLearnEnum extends StatusEnum
{
    public const LEARN = 'learn';
    public const WORK = 'work';

    public static function keys(): array
    {
        return [
            self::LEARN,
            self::WORK,
        ];
    }
}
