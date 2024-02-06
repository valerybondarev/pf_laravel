<?php

namespace App\Services;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationsMapper
{
    public function join(ConstraintViolationListInterface $violations): string
    {
        $errors = '';

        foreach ($violations as $violation) {
            $errors .= sprintf('%s: %s\n', $violation->getPropertyPath(), $violation->getMessage());
        }

        return $errors;
    }
}
