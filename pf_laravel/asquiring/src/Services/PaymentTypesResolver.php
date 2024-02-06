<?php

namespace App\Services;

class PaymentTypesResolver
{
    private const VAR_DEFAULT = 'DEFAULT';
    private const DEFAULT_TYPES = ['sber-card', 'alfa-qr'];

    public function __construct(private $paymentTypes)
    {
    }

    public function getPaymentTypes(string $source): array
    {
        $values = $this->paymentTypes[$source] ?? $this->paymentTypes[self::VAR_DEFAULT];
        if (!empty($values)) {
            return explode(',', $values);
        }

        return self::DEFAULT_TYPES;
    }
}
