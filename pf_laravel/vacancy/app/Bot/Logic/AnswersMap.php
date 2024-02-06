<?php

namespace App\Bot\Logic;

use App;
use App\Bot\Helpers\DebugTnHelper;
use App\Bot\Logic\Main\Answer\ClientAnswer;
use App\Bot\Logic\Main\Answer\MailingButtonAnswer;
use App\Bot\Logic\Main\Answer\MainAnswer;
use App\Bot\Logic\Main\Answer\RegisterAnswer;
use App\Bot\Logic\Main\Answer\WalletAnswer;
use App\Bot\Store\UserState;
use Illuminate\Contracts\Container\BindingResolutionException;

class AnswersMap
{
    /**
     * @var string[]
     */
    private array $answers = [
        'main'          => MainAnswer::class,
        'client'        => ClientAnswer::class,
        'register'      => RegisterAnswer::class,
        'wallet'        => WalletAnswer::class,
        'mailingButton' => MailingButtonAnswer::class,
    ];

    /**
     * @param UserState $userState
     *
     * @throws BindingResolutionException
     */
    public function start(UserState $userState): void
    {
        if (isset($this->answers[$userState->inlineController])) {
            $answer = App::makeWith($this->answers[$userState->inlineController], ['userState' => $userState]);
            if (method_exists($answer, $userState->inlineAction)) {
                $function = $userState->inlineAction;
                $answer->$function();
            } else {
                DebugTnHelper::write('Action ' . $userState->inlineAction . '  в Answer  не найден ' . $userState->inlineController);
            }
        } else {
            DebugTnHelper::write('Answer не найден ' . $userState->inlineController);
        }
    }

    public function checkCommand($callbackData): bool
    {
        if ($command = json_decode($callbackData, true)) {
            if ($command[0] == 'notPush') { //Кнопка заглушка
                return true;
            }
            [$inlineController, $inlineAction] = explode('.', $command[0]);
            if (isset($this->answers[$inlineController])) {
                if (method_exists($this->answers[$inlineController], $inlineAction)) {
                    return true;
                }
                DebugTnHelper::write('Метод' . $inlineAction . 'Не найден');
            } else {
                DebugTnHelper::write('Answer не найден ' . $inlineController . ' Надо добавить в AnswersMap');
            }
        }
        return false;
    }
}
