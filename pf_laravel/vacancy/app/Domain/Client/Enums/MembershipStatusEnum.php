<?php

namespace App\Domain\Client\Enums;


use App\Base\Enums\StatusEnum;

/**
 * This is the enum class for table "memberships".
 * Class App\Domain\Client\Enums\MembershipStatusEnum
 *
 * @package  App\Domain\Client\Enums
*/

class MembershipStatusEnum extends StatusEnum
{
    public const ACTIVE = 'active';
    public const BANNED = 'banned';
    public const INACTIVE = 'inactive';
    public const DELETED = 'deleted';

    public static function keys(): array
    {
        return [
            self::ACTIVE,
            self::BANNED,
            self::INACTIVE,
            self::DELETED,
        ];
    }
}
