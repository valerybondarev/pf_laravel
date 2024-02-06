<?php

namespace App\Dto\Controller;

use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ContractorJuridicalSection
{
    private const RUSSIA_COUNTRY = '643';

    #[Assert\NotBlank(message: '"name" not specified.')]
    #[Property(description: 'Наименование юр. лица')]
    #[SerializedName('name')]
    public string $name;

    #[Assert\NotBlank(message: '"FullName" not specified.')]
    #[Property(description: 'Полное наименование')]
    #[SerializedName('full_name')]
    public string $fullName;

    #[Property(description: 'ИНН')]
    #[SerializedName('inn')]
    public ?string $inn = null;

    #[Property(description: 'КПП')]
    #[SerializedName('kpp')]
    public ?string $kpp = null;

    #[Property(description: 'ОКПО')]
    #[SerializedName('okpo')]
    public ?string $okpo = null;

    #[Property(description: 'Страна выдачи')]
    #[SerializedName('country_code')]
    public ?string $countryCode = null;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (self::RUSSIA_COUNTRY === $this->countryCode) {
            if (empty($this->inn)) {
                $context->addViolation('"inn" not specified.');
            }
            if (empty($this->kpp)) {
                $context->addViolation('"kpp" not specified.');
            }
        }
    }
}
