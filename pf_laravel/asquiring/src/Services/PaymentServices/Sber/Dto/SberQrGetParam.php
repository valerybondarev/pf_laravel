<?php

namespace App\Services\PaymentServices\Sber\Dto;

class SberQrGetParam
{
    public function __construct(public string $mdOrder)
    {
    }
}
