<?php

namespace App\Domain\Application\Language\Extensions;

use App\Domain\Application\Language\Repositories\LanguageRepository;
use Illuminate\Validation\Validator;

class LanguageValidator
{
    public const RULE = 'language';

    private LanguageRepository $languageRepository;

    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    public function validate($attribute, $value, $parameters, Validator $validator)
    {
        return (bool)($this->languageRepository->find(['code' => $value]) ?: $this->languageRepository->one($value));
    }
}
