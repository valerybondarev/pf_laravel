<?php

namespace App\Exception;

use App\Enum\ErrorCode;

class ExternalSystemException extends \Exception
{
    public static function fromErrorCode(int $code, ?string $add = ''): self
    {
        return new self(ErrorCode::make($code, $add), $code);
    }
}
