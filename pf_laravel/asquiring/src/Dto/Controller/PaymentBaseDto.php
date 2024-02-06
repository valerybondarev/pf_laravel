<?php

namespace App\Dto\Controller;

use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class PaymentBaseDto
{
    #[Assert\NotBlank(null, '"unit_id" not specified.')]
    #[SerializedName('unit_id')]
    #[Property(description: 'ID продукта')]
    public string $unitId;

    #[Assert\NotBlank(null, '"product" not specified.')]
    #[SerializedName('product')]
    #[Property(description: 'Наименование страхового продукта: osago|kasko|regress...')]
    public string $product;

    #[Assert\NotNull(null, '"amount" not specified.')]
    #[Assert\GreaterThan(0, null, '"amount" must be greater than 0.')]
    #[Property(description: 'Сумма платежа')]
    public float $amount;

    #[SerializedName('payment_number')]
    #[Property(description: 'Номер платежа')]
    public ?int $paymentNumber = null;

    #[SerializedName('pay_number')]
    #[Property(description: 'Номер оплаты')]
    public ?int $payNumber = null;
}
