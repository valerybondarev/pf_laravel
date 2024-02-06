<?php

namespace App\Base\Interfaces;

interface EnumInterface
{
    public static function keys(): array;

    public static function labels(): array;
}
