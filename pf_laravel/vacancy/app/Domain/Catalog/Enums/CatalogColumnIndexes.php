<?php

namespace App\Domain\Catalog\Enums;

use App\Base\Enums\StatusEnum;

class CatalogColumnIndexes extends StatusEnum
{
    public const ID_IN_CATALOG = 0;
    public const CATEGORY      = 1;
    public const SUBCATEGORY   = 2;
    public const TITLE         = 3;
    public const VENDOR_CODE   = 4;
    public const IMPORTED_AT   = 5;
    public const PRICE         = 6;
    public const STOCK         = 7;

    public static function keys(): array
    {
        return [
            self::ID_IN_CATALOG,
            self::CATEGORY,
            self::SUBCATEGORY,
            self::TITLE,
            self::VENDOR_CODE,
            self::IMPORTED_AT,
            self::PRICE,
            self::STOCK,
        ];
    }

    public static function labels(): array
    {
        return [//['ID', 'Категория', 'Подкатегория', 'Производитель', 'Описание', 'Доступный остаток (общий без резервов)'];
                self::ID_IN_CATALOG => 'Номер',
                self::CATEGORY      => 'Категория',
                self::SUBCATEGORY   => 'Подкатегория',
                self::TITLE         => 'Наименование',
                self::VENDOR_CODE   => 'Код',
                self::IMPORTED_AT   => 'Дата принятия',
                self::PRICE         => 'Стоимость',
                self::STOCK         => 'Остаток',
        ];
    }

    public static function dbProductKeys(): array
    {
        return [//['ID', 'Категория', 'Подкатегория', 'Производитель', 'Описание', 'Доступный остаток (общий без резервов)'];
                self::ID_IN_CATALOG => 'id_in_catalog',
                self::CATEGORY      => 'category',
                self::SUBCATEGORY   => 'subcategory',
                self::TITLE         => 'title',
                self::VENDOR_CODE   => 'vendor_code',
                self::IMPORTED_AT   => 'imported_at',
                self::PRICE         => 'price',
                self::STOCK         => 'stock',
        ];
    }

    public static function getIndexByConst($const)
    {
        return array_search($const, self::keys());
    }
}
