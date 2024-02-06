<?php

namespace App\Enum;

class Source
{
    public const B2B = 'B2B';
    public const B2C = 'B2C';
    public const API = 'API';
    public const LOTUS = 'LOTUS';
    public const REPORT = 'REPORT';
    public const UCS = 'UCS';

    public const SOURCES = [self::B2B, self::B2C, self::API, self::LOTUS, self::UCS, self::REPORT];
}
