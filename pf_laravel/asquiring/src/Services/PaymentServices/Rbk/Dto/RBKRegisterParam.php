<?php

namespace App\Services\PaymentServices\Rbk\Dto;

class RBKRegisterParam
{
    public \DateTimeInterface $dueDate;
    public int $amount;
    public string $currency = 'RUB';
    public string $product;
    public array $metadata;

    public function __construct(string $orderId, int $amount, ?\DateTimeInterface $dueDate)
    {
        $this->product = $orderId;
        $this->metadata['orderId'] = $orderId;
        $this->amount = $amount;
        $this->dueDate = $dueDate;
    }
}
