<?php

namespace App\Bot\Helpers;

class TextHelper
{
    public static function mb_lcfirst($string, $charset = 'UTF-8'): string
    {
        return mb_strtolower(mb_substr($string, 0, 1, $charset), $charset)
               . mb_substr($string, 1, mb_strlen($string, $charset), $charset);
    }

    public static function mb_ucfirst($string, $charset = 'UTF-8'): string
    {
        return mb_strtoupper(mb_substr($string, 0, 1, $charset), $charset)
               . mb_substr($string, 1, mb_strlen($string, $charset), $charset);
    }

    /**
     * Добавляет или удаляет строку из строки перечислений
     * Например имеем список раз,два,три
     * Вызываем addOrDeleteStrInListStr(раз, раз,два,три, ',') - Результат два,три
     *
     * Вызываем addOrDeleteStrInListStr(четыре, раз,два,три, ',') - Результат раз,два,три,четыре
     *
     * @param string $value
     * @param string $valuesString
     * @param string $separator
     * @param bool   $lcfirst
     *
     * @return string
     */
    public static function addOrDeleteStrInListStr(string $value, string $valuesString, string $separator = ',', bool $lcfirst = true): string
    {
        $result = $valuesString;
        if ($value) {
            iF ($lcfirst) {
                $value = self::mb_lcfirst($value);
            }
            if (!$valuesString || !str_contains($valuesString, $value)) {
                $result = $valuesString ? $valuesString . $separator . $value : $value;
            } else {
                $values = explode($separator, $valuesString);
                unset($values[array_search($value, $values)]);
                $result = count($values) > 0 ? implode($separator, $values) : '';
            }
        }
        return $result;
    }
}
