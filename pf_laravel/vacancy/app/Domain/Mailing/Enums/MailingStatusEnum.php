<?php

namespace App\Domain\Mailing\Enums;


use App\Base\Enums\StatusEnum;

/**
 * This is the enum class for table "mailings".
 * Class App\Domain\Mailing\Enums\MailingStatusEnum
 *
 * @package  App\Domain\Mailing\Enums
 */
class MailingStatusEnum extends StatusEnum
{
    public const SEND     = 'send';
    public const ACTIVE   = 'active';
    public const INACTIVE = 'inactive';
    public const DELETED  = 'deleted';

    public static function keys(): array
    {
        return [
            self::SEND,
            self::ACTIVE,
            self::INACTIVE,
        ];
    }
}
