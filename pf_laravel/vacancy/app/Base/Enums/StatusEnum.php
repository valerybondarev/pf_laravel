<?php

namespace App\Base\Enums;

class StatusEnum extends BaseEnum
{
    public const ACTIVE = 'active';
    public const INACTIVE = 'inactive';
    public const BANNED = 'banned';
    public const DELETED = 'deleted';

    public static function keys(): array
    {
        return [
            self::ACTIVE,
            self::INACTIVE,
            self::BANNED,
            self::DELETED,
        ];
    }

    public static function labels(): array
    {
        return collect(static::keys())
            ->flip()
            ->mapWithKeys(function ($value, $key) {
                return [$key => __("admin.statuses.$key")];
            })
            ->all();
    }
}
