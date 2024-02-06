<?php

namespace App\Bot\Helpers;

use Validator;

class RuleHelper
{
    /**
     * Валидация ссылки
     * @param $url
     *
     * @return bool
     */
    public static function validateUrl($url): bool
    {
        $validator = Validator::make(['url' => $url], [
            'url' => 'required|website',
        ]);
        return !$validator->fails();
    }
}
