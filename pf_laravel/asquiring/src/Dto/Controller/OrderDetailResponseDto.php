<?php

namespace App\Dto\Controller;

use OpenApi\Annotations as OA;
use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Annotation\SerializedName;

class OrderDetailResponseDto
{
    #[Property(description: 'uuid записи таблицы Orders')]
    public string $id;

    #[Property(description: 'Статус записи')]
    public string $status;

    #[Property(description: 'Тип записи - клиентский или агентский')]
    public string $type;

    #[SerializedName('created_at')]
    #[Property(description: 'Дата создания записи')]
    public \DateTimeInterface $createdAt;

    #[SerializedName('total_amount')]
    #[Property(description: 'Сумма')]
    public float $amount;

    #[SerializedName('return_url')]
    #[Property(description: 'Адрес возврата')]
    public ?string $returnUrl;

    #[SerializedName('expires_at')]
    #[Property(description: 'Дата, до которой необходимо внести оплату по полису')]
    public ?\DateTimeInterface $expiresAt;

    /**
     * @OA\Property(type="array", @OA\Items(type="string"), description="Данные по назначению платежа")
     */
    public array $detail;

    /**
     * @OA\Property(type="array", @OA\Items(type="string"), description="Данные по вариантам оплаты")
     */
    #[SerializedName('payment_types')]
    public array $paymentTypes;

    #[Property(description: 'Признак ошибки')]
    public bool $success = true;
}
