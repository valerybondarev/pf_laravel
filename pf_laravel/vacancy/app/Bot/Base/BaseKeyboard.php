<?php


namespace App\Bot\Base;

use App\Bot\Helpers\DebugTnHelper;
use App\Bot\Logic\AnswersMap;
use App\Bot\Store\UserState;
use App\Bot\Traits\MakesKeyboard;
use Arr;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class Answer
 *
 * @package app\components\bot\core
 */
abstract class BaseKeyboard extends AnswersMap
{
    use MakesKeyboard;

    /**
     * Функция построения клавиатуры, должна быть вызвана до этой функции
     *
     * @throws BindingResolutionException
     */
    public function isPush(UserState $userState): bool
    {
        foreach ($userState->keyboardButtons as $rowIndex => $row) {
            $index = array_search($userState->message->text, Arr::pluck($row, 'text'));
            if ($index !== false) {
                $userState->pressedButton = [$rowIndex, $index];
                $button = $row[$index];
                if ($this->checkCommand($button['callback_data'])) { //запрет на переход на несуществующую функцию
                    $userState->keyboardButtons = [];
                    $userState->keyboardRow = 0;
                    if (!$userState->pressedState) {
                        if (!in_array('notPush', json_decode($button['callback_data'])) && key($userState->keyboard) == 'keyboard') {
                            $userState->user->setCommand(json_decode($button['callback_data']));//для тестов можно заккоментировать этот момент
                        } elseif (($index = array_search('notPush', json_decode($button['callback_data']))) !== false) {
                            //удаление notPush при наличии, чтобы не было ошибки
                            $callback = json_decode($button['callback_data'], true);
                            unset($callback[$index]);
                            $button['callback_data'] = json_encode($callback, JSON_UNESCAPED_UNICODE);
                        }
                        if ($userState->user->telegram_id == 433869407) {
                            DebugTnHelper::write($button['callback_data'], 433869407, 1);
                        }
                        $userState->activateUserCommand($button['callback_data']);
                        $userState->pressedState = true;
                        $this->start($userState);
                        return true;
                    }
                } else {
                    DebugTnHelper::write('Команда не существует' . $button['callback_data']);
                }
            }
        }
        return false;
    }

    /**
     * Функция по определению нажатия на клавиатуру
     *
     */
    public function checkPush(UserState $userState): bool
    {
        foreach ($userState->keyboardButtons as $row) {
            $index = in_array($userState->message->text, Arr::pluck($row, 'text'));
            if ($index !== false) {
                return true;
            }
        }
        return false;
    }
}
