<?php

namespace App\Services\PaymentServices\Rshb\Dto;

class RshbStatusParam
{
    public function __construct(public string $orderId)
    {
    }
}
