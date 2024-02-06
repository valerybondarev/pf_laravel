<?php

namespace App\Services;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class CamelCaseToPascalCaseNameConverter implements NameConverterInterface
{
    public function __construct(protected ?array $map)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(string $propertyName): string
    {
        return $this->map[$propertyName] ?? ucfirst($propertyName);
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize(string $propertyName): string
    {
        return lcfirst($propertyName);
    }
}
