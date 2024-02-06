<?php

namespace App\Bot\Helpers;

use App\Bot\Store\UserState;
use App\Domain\Technic\Entities\TechnicTypeParam;
use App\Domain\Technic\Enums\TechnicTypeParamTypeEnum;
use Exception;
use Validator;

trait ValidateHelper
{
    /**
     * Проверяет номер телефона на валидность, и возвращает номер телефона в формате 79171234567
     *
     * @param UserState $userState
     * @param int       $length
     * @param null      $text
     *
     * @return string
     * @throws Exception
     */
    private function validateTextLength(UserState $userState, int $length = 255, $text = null): string
    {
        if (mb_strlen($userState->message->text) < $length) {
            return $userState->message->text;
        }

        $userState->messageText = $text ?? "Длина текста не должна превышать $length символов";
        $userState->sendMessage();
        $errorText = "Пользователь {$userState->user->id} ввел текст длинее $length";
        $errorText .= PHP_EOL . "Введенное значение {$userState->message->text}" . PHP_EOL . __CLASS__ . PHP_EOL . __FUNCTION__;
        throw new Exception($errorText, 406);
    }

    /**
     * Проверяет номер телефона на валидность, и возвращает номер телефона в формате 79171234567
     *
     * @param UserState $userState
     *
     * @return string
     * @throws Exception
     */
    private function validatePhone(UserState $userState): string
    {
        $enteredText = preg_replace("/[^+0-9]/", '', $userState->message->text);
        if (preg_match('/^(\+7|8|7)[0-9]{10}/', $enteredText, $matches) && $enteredText != '89991234567') {
            if ((string)$matches[1] === "7") {
                DebugTnHelper::write($matches);
                return $matches[0];
            }
            //return '7' . preg_replace("/$matches[1]/", '', $matches[0], 1);
            DebugTnHelper::write($matches);
            return '8' . substr($matches[0], strlen($matches[1]));
        }
        if ($enteredText == '89991234567') {
            $userState->messageText = '89991234567 - это всего лишь пример, пиши свой номер телефона😉';
        } else {
            $userState->messageText =
                'Вы ввели номер телефона не по формату 89991234567, введите пожалуйста номер телефона по формату';
        }
        $userState->sendMessage();
        $errorText = "Пользователь {$userState->user->id} {$userState->user->first_name} {$userState->user->last_name} ввел номер телефона не в формате";
        $errorText .= PHP_EOL . "Введенное значение {$userState->message->text}" . PHP_EOL . __CLASS__ . PHP_EOL . __FUNCTION__;
        throw new Exception($errorText, 406);
    }

    /**
     * Проверяет электронную почту на валидность, и возвращает значение
     *
     * @param UserState $userState
     *
     * @return string
     * @throws Exception
     */
    private function validateEmail(UserState $userState): string
    {
        $enteredText = $userState->message->text;
        $validator = Validator::make(['email' => $enteredText], [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $userState->messageText = 'Пришлите электронную почту для отправки заполненного договора';
            $userState->sendMessage();
            $errorText = "Пользователь {$userState->user->id} ввел электронную почту не в формате";
            $errorText .= PHP_EOL . "Введенное значение {$userState->message->text}" . PHP_EOL . __CLASS__ . PHP_EOL . __FUNCTION__;
            throw new Exception($errorText, 406);
        }
        return $enteredText;
    }

    /**
     * Проверка введенного параметра техники пользователя
     *
     * @throws Exception
     */
    private function validateEnteredParam(UserState $userState, TechnicTypeParam $technicTypeParam): ?string
    {
        if ($technicTypeParam->type == TechnicTypeParamTypeEnum::LIST && /*$technicTypeParam->other_button != 1*/($technicTypeParam->multiple || !$technicTypeParam->other_button)) {
            //В этот раздел попадают, если человек должен выбрать список и написал значение
            $error = false;
            if (!isset($userState->inlineCommand[1][2])) {
                $error = true;
            } //else {
            //    $indexInList = $userState->inlineCommand[1][2];
            //    $list = explode(',', $technicTypeParam->list);
            //    $value = $list[$indexInList];
            //}
            //
            if ($error) {
                $userState->messageText = 'Выберите параметр из списка, нажав на клавиатуру';
                $userState->sendMessage();
                $errorText =
                    "Пользователь {$userState->user->id} Пытается ввести значение $technicTypeParam->title хотя нужно выбрать из списка";
                $errorText .= PHP_EOL . "Введенное значение {$userState->message->text}" . PHP_EOL . __CLASS__ . PHP_EOL
                              . __FUNCTION__;
                throw new Exception($errorText, 406);
            }
        } else {
            if ($technicTypeParam->type == TechnicTypeParamTypeEnum::NUMERIC) {
                if (is_numeric($userState->message->text)) {
                    $value = $userState->message->text;
                } else {
                    $userState->messageText = 'То что вы ввели не числовое значение, введите пожалуйста число';
                    $userState->sendMessage();
                    $errorText = "Пользователь {$userState->user->id} ввел значение параметра $technicTypeParam->title не как число";
                    $errorText .= PHP_EOL . "Введенное значение {$userState->message->text}" . PHP_EOL . __CLASS__ . PHP_EOL . __FUNCTION__;
                    throw new Exception($errorText, 406);
                }
            } else {
                $value = $userState->message->text;
            }
        }
        return $value;
    }
}
