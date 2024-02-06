<?php

namespace App\Base\Enums;

use App\Base\Interfaces\EnumInterface;
use InvalidArgumentException;


abstract class BaseEnum implements EnumInterface
{
    abstract public static function keys(): array;

    public static function contains($key): bool
    {
        return in_array($key, static::keys(), true);
    }

    public static function label($key): mixed
    {
        $labels = static::labels();

        if (array_key_exists($key, $labels)) {
            return $labels[$key];
        }

        throw new InvalidArgumentException("Unknown enum: $key");
    }

    public static function labels(): array
    {
        return array_combine(static::keys(), static::keys());
    }
}
