<?php

namespace App\Domain\Catalog\Enums;

use App\Base\Enums\StatusEnum;

class CatalogImportJobStatus extends StatusEnum
{
    public const WAIT = 'wait';
    public const WORK = 'work';
    public const SUCCESS = 'success';
    public const ERROR = 'error';
    public static function keys(): array
    {
        return [
            self::WAIT,
            self::WORK,
            self::SUCCESS,
            self::ERROR,
        ];
    }
}
