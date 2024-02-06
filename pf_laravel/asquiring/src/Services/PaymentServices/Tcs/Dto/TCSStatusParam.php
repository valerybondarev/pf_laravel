<?php

namespace App\Services\PaymentServices\Tcs\Dto;

final class TCSStatusParam
{
    public function __construct(public int $PaymentId)
    {
    }
}
