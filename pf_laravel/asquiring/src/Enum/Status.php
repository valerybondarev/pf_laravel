<?php

namespace App\Enum;

class Status
{
    public const CREATED = 'created';
    public const VISITED = 'visited';
    public const PROCESS = 'process';
    public const SUCCESS = 'success';
    public const VERIFIED = 'verified';
    public const FAIL = 'fail';
    public const UNVERIFIED = 'unverified';

    public const ALLOWED_TO_PAYMENT_CREATE = [self::CREATED, self::VISITED, self::FAIL, self::UNVERIFIED];

    public static function allowNewPayment(string $status): bool
    {
        return in_array($status, self::ALLOWED_TO_PAYMENT_CREATE);
    }

    public static function isSuccessful(string $status): bool
    {
        return self::SUCCESS === $status;
    }

    public static function isVerified(string $status): bool
    {
        return self::VERIFIED === $status;
    }
}
