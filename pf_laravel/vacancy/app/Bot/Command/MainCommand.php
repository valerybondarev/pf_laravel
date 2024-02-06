<?php

namespace App\Bot\Command;

use App;
use App\Bot\Logic\Main\Answer\MainAnswer;
use App\Bot\Store\UserState;

class MainCommand
{
    public function __construct(private UserState $userState)
    {
    }

    public function handle($command)
    {
        switch ($command) {
            case 'start':
                $this->userState->user->setCommand(['main.main']);
                /** @var MainAnswer $mainAnswer */
                $mainAnswer = App::makeWith(MainAnswer::class, ['userState' => $this->userState]);
                $mainAnswer->main(false);
                break;
            case 'welcome':
                $this->userState->user->setCommand(['main.main']);
                /** @var MainAnswer $mainAnswer */
                $mainAnswer = App::makeWith(MainAnswer::class, ['userState' => $this->userState]);
                $mainAnswer->welcome();
                break;
            default:
                $this->userState->messageText = 'Команда не найдена..';
                $this->userState->sendMessage();
        }
    }
}
