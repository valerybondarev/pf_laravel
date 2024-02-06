<?php

namespace App\Dto\Controller;

use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Annotation\SerializedName;

class OrderCreateResponseDto
{
    #[Property(description: 'Идентификатор нового заказа (uuid)')]
    public string $id;

    #[Property(description: 'Статус заказа, для новой всегда created')]
    public string $status;

    #[SerializedName('created_at')]
    #[Property(description: 'Время создания заказа')]
    public \DateTimeInterface $createdAt;

    #[SerializedName('expires_at')]
    #[Property(description: 'Время, до которой необходимо внести оплату')]
    public ?\DateTimeInterface $expiresAt;

    #[Property(description: 'Признак ошибки')]
    public bool $success = true;
}
