<?php

namespace App\Exception;

use App\Enum\ErrorCode;
use App\Intf\PaymentExceptionInterface;

class PaymentException extends \Exception implements PaymentExceptionInterface
{
    public static function fromErrorCode(int $code, ?string $add = ''): self
    {
        return new self(ErrorCode::make($code, $add), $code);
    }
}
