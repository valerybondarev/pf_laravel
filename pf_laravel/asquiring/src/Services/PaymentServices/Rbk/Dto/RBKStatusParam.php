<?php

namespace App\Services\PaymentServices\Rbk\Dto;

class RBKStatusParam
{
    public string $invoiceId;

    public function __construct(string $invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }
}
