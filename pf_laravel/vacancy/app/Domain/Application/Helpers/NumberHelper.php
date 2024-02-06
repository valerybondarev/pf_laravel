<?php


namespace App\Domain\Application\Helpers;


class NumberHelper
{
    public static function format($number): string
    {
        return number_format($number, 0, ',', ' ');
    }

}
