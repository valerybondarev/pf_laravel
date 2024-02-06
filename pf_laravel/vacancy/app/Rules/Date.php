<?php

namespace App\Rules;

use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

class Date implements Rule
{

    public function passes($attribute, $value): bool
    {
        try {
            Carbon::make($value);
            return true;
        } catch (InvalidFormatException) {
            return false;
        }
    }

    public function message(): ?string
    {
        return __('validation.date');
    }
}