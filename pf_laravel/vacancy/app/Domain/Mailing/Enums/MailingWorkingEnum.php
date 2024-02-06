<?php

namespace App\Domain\Mailing\Enums;


use App\Base\Enums\StatusEnum;

/**
 * This is the enum class for table "mailings".
 * Class App\Domain\Mailing\Enums\MailingStatusEnum
 *
 * @package  App\Domain\Mailing\Enums
 */
class MailingWorkingEnum extends StatusEnum
{
    public const WORKING     = 1;
    public const NOT_WORKING = 0;

    public static function keys(): array
    {
        return [
            self::WORKING,
            self::NOT_WORKING,
        ];
    }
}
