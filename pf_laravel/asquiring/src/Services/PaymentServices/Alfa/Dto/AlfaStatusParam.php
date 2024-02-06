<?php

namespace App\Services\PaymentServices\Alfa\Dto;

class AlfaStatusParam
{
    public function __construct(public string $orderId)
    {
    }
}
