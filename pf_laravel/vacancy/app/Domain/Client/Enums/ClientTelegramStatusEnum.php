<?php

namespace App\Domain\Client\Enums;


use App\Base\Enums\StatusEnum;

/**
 * This is the enum class for table "client_lists".
 * Class App\Domain\Client\Enums\ClientListStatusEnum
 *
 * @package  App\Domain\Client\Enums
*/

class ClientTelegramStatusEnum extends StatusEnum
{
    public const MEMBER = 'member';
    public const KICKED = 'kicked';

    public static function keys(): array
    {
        return [
            self::MEMBER,
            self::KICKED,
        ];
    }
}
