<?php

namespace App\Services\PaymentServices\Tcs\Dto;

final class TCSQrGetParam
{
    public function __construct(public string $PaymentId)
    {
    }
}
