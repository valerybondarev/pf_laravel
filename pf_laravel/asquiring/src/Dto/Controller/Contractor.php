<?php

namespace App\Dto\Controller;

use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Contractor
{
    public const CONTRACTOR_JURIDICAL = 'ЮрЛицо';
    public const CONTRACTOR_PHISICAL = 'ФизЛицо';

    public const CONTRACTOR_TYPES = [self::CONTRACTOR_JURIDICAL, self::CONTRACTOR_PHISICAL];

    #[Assert\NotBlank(message: '"contractor_type" not specified.')]
    #[Assert\Choice(choices: Contractor::CONTRACTOR_TYPES, message: 'Тип плательщика должен быть "ЮрЛицо" или "ФизЛицо"')]
    #[Property(description: 'Тип плательщика: ЮрЛицо|ФизЛицо')]
    #[SerializedName('contractor_type')]
    public string $contractorType = '';

    #[Assert\Type(type: 'bool', message: 'Признак Не резидент должен иметь значение true либо false')]
    #[Property(description: 'Резидент|Не резидент')]
    #[SerializedName('non_resident')]
    public bool $nonresident;

    #[Assert\Valid]
    #[Property(description: 'Плательщик - ЮрЛицо')]
    #[SerializedName('juridical_section')]
    public ?ContractorJuridicalSection $juridicalSection;

    #[Assert\Valid]
    #[Property(description: 'Плательщик - ФизЛицо')]
    #[SerializedName('phisical_section')]
    public ?ContractorPhisicalSection $phisicalSection;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (self::CONTRACTOR_PHISICAL === $this->contractorType && empty($this->phisicalSection)) {
            $context->addViolation('"phisical_section" must be specified.');
        }
        if (self::CONTRACTOR_JURIDICAL === $this->contractorType && empty($this->juridicalSection)) {
            $context->addViolation('"juridical_section" must be specified.');
        }
    }

    #[Ignore]
    public function getName(): string
    {
        if (self::CONTRACTOR_PHISICAL === $this->contractorType) {
            return $this->phisicalSection->getSurname() . ' ' . $this->phisicalSection->getName() . ' ' . $this->phisicalSection->getMiddleName();
        } elseif (self::CONTRACTOR_JURIDICAL === $this->contractorType) {
            return $this->juridicalSection->name;
        }

        return '';
    }

    #[Ignore]
    public function getInn(): ?string
    {
        if (self::CONTRACTOR_JURIDICAL === $this->contractorType) {
            return $this->juridicalSection->inn;
        }

        return null;
    }
}
