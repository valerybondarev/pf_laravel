<?php

namespace App\Enum;

use App\Exception\PaymentException;

class Provider
{
    public const ALFA = 'alfa';
    public const RBK = 'rbk';
    public const CASH = 'cash';
    public const TCS = 'tinkoff';
    public const SBER = 'sber';
    public const RSHB = 'rshb';

    public const PROVIDERS = [self::ALFA, self::SBER, self::RSHB];

    /**
     * @throws PaymentException
     */
    public static function of(string $value): string
    {
        if (!in_array($value, self::PROVIDERS)) {
            throw PaymentException::fromErrorCode(ErrorCode::UNKNOWN_PROVIDER, $value);
        }

        return $value;
    }
}
