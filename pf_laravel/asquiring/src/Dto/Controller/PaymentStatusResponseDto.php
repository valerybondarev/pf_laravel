<?php

namespace App\Dto\Controller;

use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Annotation\SerializedName;

class PaymentStatusResponseDto
{
    #[SerializedName('order_id')]
    #[Property(description: 'uuid записи в таблице Orders')]
    public string $orderId;

    #[SerializedName('payment_id')]
    #[Property(description: 'uuid записи в таблице Payments')]
    public string $paymentId;

    #[SerializedName('paid_status')]
    #[Property(description: 'Статус записи в таблице Payments')]
    public string $paidStatus;
}
