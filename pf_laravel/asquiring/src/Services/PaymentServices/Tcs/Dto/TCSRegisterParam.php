<?php

namespace App\Services\PaymentServices\Tcs\Dto;

final class TCSRegisterParam
{
    public function __construct(
        public string $OrderId,
        public int $Amount,
        public ?\DateTimeInterface $RedirectDueDate
        ) {
    }
}
