<?php

namespace App\Dto\Controller;

use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Annotation\SerializedName;

class PaymentLinkResponseDto
{
    #[SerializedName('payment_url')]
    #[Property(description: 'Ссылка на оплату')]
    public string $paymentUrl;

    #[Property(description: 'Признак ошибки')]
    public bool $success = true;
}
