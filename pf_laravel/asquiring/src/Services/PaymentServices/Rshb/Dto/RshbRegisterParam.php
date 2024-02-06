<?php

namespace App\Services\PaymentServices\Rshb\Dto;

class RshbRegisterParam
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
