<?php

namespace App\Services\ExternalSystem\B2B\Dto;

class PaymentNotifyParam
{
    public function __construct(public array $products)
    {
    }
}