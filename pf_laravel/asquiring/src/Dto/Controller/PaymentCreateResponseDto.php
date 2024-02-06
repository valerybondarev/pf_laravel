<?php

namespace App\Dto\Controller;

use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Annotation\SerializedName;

class PaymentCreateResponseDto
{
    #[Property(description: 'uuid записи в таблице Payments')]
    public string $id;

    #[SerializedName('invoice_id')]
    #[Property(description: 'Номер заказа в системе ПС')]
    public string $invoiceId;

    #[SerializedName('payment_url')]
    #[Property(description: 'Ссылка на оплату')]
    public string $paymentUrl;

    #[SerializedName('created_at')]
    #[Property(description: 'Дата создания записи')]
    public \DateTimeInterface $createdAt;

    #[Property(description: 'Признак ошибки')]
    public bool $success = true;
}
