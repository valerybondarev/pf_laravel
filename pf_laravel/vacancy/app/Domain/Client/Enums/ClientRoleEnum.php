<?php

namespace App\Domain\Client\Enums;


use App\Base\Enums\BaseEnum;

/**
 * This is the enum class for table "clients".
 * Class App\Domain\Client\Enums\ClientRoleEnum
 *
 * @package  App\Domain\Client\Enums
*/

class ClientRoleEnum extends BaseEnum
{
    public const USER = 'user';
    public const MODERATOR = 'moderator';
    public const ADMIN = 'admin';

    public static function keys(): array
    {
        return [
            self::USER,
            self::MODERATOR,
            self::ADMIN,
        ];
    }

    public static function labels(): array
    {
        return collect(static::keys())
            ->flip()
            ->mapWithKeys(function ($value, $key) {
                return [$key => __("admin.roles.$key")];
            })
            ->all();
    }
}
