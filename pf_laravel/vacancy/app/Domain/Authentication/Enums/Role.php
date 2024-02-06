<?php

namespace App\Domain\Authentication\Enums;

use App\Base\Enums\BaseEnum;

class Role extends BaseEnum
{
    public const ROOT  = 'root';
    public const ADMIN = 'admin';

    public static array $permissions = [
        self::ROOT  => [
            Permission::SYSTEM,
            Permission::ADMIN,
        ],
        self::ADMIN => [
            Permission::ADMIN,
        ]
    ];

    public static function keys(): array
    {
        return [
            self::ROOT,
            self::ADMIN,
        ];
    }

    public static function labels(): array
    {
        return [
            self::ROOT  => 'Root',
            self::ADMIN => 'Admin',
        ];
    }
}
