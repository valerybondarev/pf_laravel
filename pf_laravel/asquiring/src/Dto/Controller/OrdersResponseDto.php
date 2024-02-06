<?php

namespace App\Dto\Controller;

use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Annotation\SerializedName;

class OrdersResponseDto
{
    #[Property(description: 'Список orders. Каждый пункт соответствует формату данных OrderResponseDto')]
    /** @var OrderResponseDto[] */
    public ?array $data;

    #[Property(description: 'Номер текущей страницы')]
    public ?int $page;

    #[SerializedName('per_page')]
    #[Property(description: 'Количество элементов на страницу')]
    public ?int $perPage;

    #[Property(description: 'Общее число элементов в списке')]
    public int $total;

    #[Property(description: 'Признак ошибки')]
    public bool $success = true;
}
