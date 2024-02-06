<?php

namespace App\Services\PaymentServices\Alfa\Dto;

class AlfaQrGetParam
{
    public function __construct(public string $mdOrder)
    {
    }
}
