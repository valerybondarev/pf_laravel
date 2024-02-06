<?php

namespace App\Enum;

class ErrorCode
{
    public const SUCCESS = 0;
    public const ORDER_NOT_FOUND = 1;
    public const INVALID_STATUS = 2;
    public const VALIDATION_ERROR = 3;
    public const ERROR_CREATING_PAYMENT = 4;
    public const UNKNOWN_PROVIDER = 5;
    public const UNKNOWN_PAYMENT_TYPE = 6;
    public const ORDER_EXPIRED = 7;
    public const UNKNOWN_SOURCE = 8;
    public const ERROR_CREATING_USC_BILL = 9;
    public const ERROR_GET_CONTRACTOR = 10;
    public const ERROR_GET_DETAIL = 11;
    public const ORDER_DELETED = 12;
    public const ERROR_PAYMENT_NOTIFICATION = 13;
    public const NO_ACTIVE_PAYMENT = 14;
    public const BAD_RESPONSE = 15;
    public const ORDER_PAYED = 16;
    public const CREATING_USC_BILL = 17;
    public const PAYMENT_NOT_FOUND = 18;

    public const ERROR_MESSAGES = [
        self::SUCCESS => 'Успешно',
        self::ORDER_NOT_FOUND => 'Ордер не найден',
        self::INVALID_STATUS => 'Неправильный статус ордера',
        self::VALIDATION_ERROR => 'Ошибка валидации',
        self::ERROR_CREATING_PAYMENT => 'Ошибка создания оплаты',
        self::UNKNOWN_PROVIDER => 'Неизвестный провайдер',
        self::UNKNOWN_PAYMENT_TYPE => 'Неизвестный вид оплаты',
        self::ORDER_EXPIRED => 'Ордер просрочен',
        self::UNKNOWN_SOURCE => 'Неизвестный источник',
        self::ERROR_CREATING_USC_BILL => 'Ошибка создания счета в УЦС',
        self::ERROR_GET_CONTRACTOR => 'Ошибка получения данных плательщика',
        self::ERROR_GET_DETAIL => 'Ошибка получения детальной информации ордера',
        self::ORDER_DELETED => 'Ордер удален',
        self::ERROR_PAYMENT_NOTIFICATION => 'Ошибка обновления статуса платежа',
        self::NO_ACTIVE_PAYMENT => 'Не найден активный платеж',
        self::BAD_RESPONSE => 'Получен ответ с ошибкой',
        self::ORDER_PAYED => 'Ордер оплачен',
        self::CREATING_USC_BILL => 'Ордер в процессе создания',
        self::PAYMENT_NOT_FOUND => 'Платеж не найден',
    ];

    public static function make(int $code, ?string $suffix = ''): string
    {
        $result = self::ERROR_MESSAGES[$code];
        if ($suffix) {
            $result .= ': ' . $suffix;
        }

        return $result;
    }
}
