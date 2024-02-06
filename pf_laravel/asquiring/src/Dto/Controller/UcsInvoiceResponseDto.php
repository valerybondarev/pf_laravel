<?php

namespace App\Dto\Controller;

use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Annotation\SerializedName;

class UcsInvoiceResponseDto
{
    #[SerializedName('invoice_id')]
    #[Property(description: 'Идентификатор счета УЦС')]
    public string $invoiceId;

    #[SerializedName('invoice_number')]
    #[Property(description: 'Номер счета УЦС')]
    public string $invoiceNumber;

    #[SerializedName('created_at')]
    #[Property(description: 'Дата создания счета')]
    public \DateTimeInterface $createdAt;

    #[Property(description: 'Признак ошибки')]
    public bool $success = true;
}
