<?php

namespace App\Dto\Controller;

use App\Services\Helper;
use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class ContractorPhisicalSection
{
    public const BIRTHDAY_FORMAT = 'Y-m-d';
    public const INVALID_DATE_VALUE = '0001-01-01';

    #[Assert\NotBlank(message: '"name" not specified.')]
    #[Property(description: 'Имя физ. лица')]
    #[SerializedName('name')]
    private string $name;

    #[Assert\NotBlank(message: '"surname" not specified.')]
    #[Property(description: 'Фамилия физ. лица')]
    #[SerializedName('surname')]
    private string $surname;

    #[Assert\NotNull(message: '"middle_name" not specified.')]
    #[Property(description: 'Отчество физ. лица')]
    #[SerializedName('middle_name')]
    private string $middleName = '';

    #[Assert\NotBlank(message: '"birthday" not specified.')]
    #[Property(description: 'Дата рождения')]
    #[SerializedName('birthday')]
    private string $birthday;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    public function setMiddleName(string $middleName): void
    {
        $this->middleName = $middleName;
    }

    public function getBirthday(): string
    {
        if (Helper::validateDate($this->birthday, self::BIRTHDAY_FORMAT)) {
            return $this->birthday;
        }

        return self::INVALID_DATE_VALUE;
    }

    public function setBirthday(string $birthday): void
    {
        $this->birthday = $birthday;
    }
}
