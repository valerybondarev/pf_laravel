<?php

namespace App\Enum;

use App\Exception\PaymentException;

class PaymentType
{
    public const CARD = 'card';
    public const QR = 'qr';

    public const PAYMENT_TYPES = [self::CARD, self::QR];

    /**
     * @throws PaymentException
     */
    public static function of(string $value): string
    {
        if (!in_array($value, self::PAYMENT_TYPES)) {
            throw PaymentException::fromErrorCode(ErrorCode::UNKNOWN_PAYMENT_TYPE, $value);
        }

        return $value;
    }
}
