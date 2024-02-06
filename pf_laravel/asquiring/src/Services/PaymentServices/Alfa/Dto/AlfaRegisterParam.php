<?php

namespace App\Services\PaymentServices\Alfa\Dto;

class AlfaRegisterParam
{
    public function __construct(
        public string $orderNumber,
        public int $amount,
        public ?\DateTimeInterface $expirationDate,
        public string $paymentDescription,
        public ?string $ucsBill = null,
        public ?string $ucsInvoiceId = null
    ) {
    }
}