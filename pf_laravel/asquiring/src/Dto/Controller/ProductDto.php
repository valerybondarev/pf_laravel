<?php

namespace App\Dto\Controller;

use OpenApi\Attributes\Property;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class ProductDto
{
    #[Assert\NotBlank(message: '"product" not specified.')]
    #[Property(description: 'Продукт')]
    public ?string $product;

    #[Assert\NotBlank(message: '"unit_id" not specified.')]
    #[Property(description: 'Ид продукта')]
    #[SerializedName('unit_id')]
    public ?string $unitId;

    #[Property(description: 'EstimationId продукта')]
    #[SerializedName('estimation_id')]
    public ?string $estimationId;

    #[Property(description: 'Номер платежа')]
    #[SerializedName('payment_number')]
    public ?string $paymentNumber;

    #[Property(description: 'Номер оплаты')]
    #[SerializedName('pay_number')]
    public ?string $payNumber;

    public static function fromRequestParams(InputBag $params): ProductDto
    {
        $dto = new ProductDto();
        $dto->product = $params->get('product');
        $dto->unitId = $params->get('unit_id');
        $dto->estimationId = $params->get('estimation_id');
        $dto->paymentNumber = !empty($params->get('payment_number')) ? $params->get('payment_number') : null;
        $dto->payNumber = !empty($params->get('pay_number')) ? $params->get('pay_number') : null;

        return $dto;
    }
}
