<?php

namespace App\Services\PaymentServices\Sber\Dto;

class SberStatusParam
{
    public function __construct(public string $orderId)
    {
    }
}
