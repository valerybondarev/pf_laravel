<?php

namespace App\Dto\Controller;

use App\Entity\Payment;
use OpenApi\Attributes\Property;

class PaymentsResponseDto
{
    #[Property(description: 'Список платежей, привязанных к выбранному order_id. Каждый пункт соответствует формату данных PaymentResponseDto')]
    /** @var Payment[] */
    public ?array $data;

    #[Property(description: 'Признак ошибки')]
    public bool $success = true;

    #[Property(description: 'Общее число элементов в списке')]
    public int $total;
}
