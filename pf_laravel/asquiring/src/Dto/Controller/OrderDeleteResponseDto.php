<?php

namespace App\Dto\Controller;

use OpenApi\Attributes\Property;

class OrderDeleteResponseDto
{
    #[Property(description: 'Идентификатор заказа (uuid)')]
    public string $id;

    #[Property(description: 'Статус ордера')]
    public string $status;

    #[Property(description: 'Признак ошибки')]
    public bool $success = true;
}
