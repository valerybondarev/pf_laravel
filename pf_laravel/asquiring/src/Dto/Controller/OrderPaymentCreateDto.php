<?php

namespace App\Dto\Controller;

use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class OrderPaymentCreateDto implements RequestDtoInterface
{
    #[Assert\NotBlank(null, '"device_type" not specified.')]
    #[SerializedName('device_type')]
    #[Property(description: 'Тип устройства')]
    public string $deviceType;

    #[Assert\NotBlank(null, '"provider" not specified.')]
    #[Property(description: 'Наименование ПС и тип платежа. Правила именования: “провайдер”-”метод”')]
    public string $provider;
}
