<?php


namespace App\Bot\Logic\Main\Keyboard;

use App\Bot\Base\BaseKeyboard;
use App\Bot\Store\UserState;

class MainKeyboard extends BaseKeyboard
{
    public function __construct(
    )
    {
    }

    public function main(UserState $userState)
    {
        $this->addBtn($userState, 'Личный кабинет', ['client.main', 'notPush'], false);
        $this->addBtn($userState, 'Мероприятия', ['main.events', 'notPush']);
        if ($userState->user->tourClub->about) {
            $this->addBtn($userState, 'О нас', ['main.about', 'notPush'], false);
        }
        if ($userState->user->tourClub->memberShipIsWork) {
            $this->addBtn($userState, 'Членство в турклубе', ['main.membershipState', 'notPush']);
        }
        $this->addBtn($userState, 'Помощь', ['main.help', 'notPush']);
    }

    public function question(UserState $userState)
    {
            $this->addInlineBtn($userState, 'Ответить', ['main.sendMessageInit', $userState->user->id]);
    }

    public function membershipState(UserState $userState)
    {
        if ($userState->user->tourClub->memberShipIsWork) {
            $this->addInlineBtn($userState, 'Вступить в турклуб', ['main.createMembership']);
        }
    }

    public function welcome(UserState $userState)
    {
        $this->addInlineBtn($userState, 'Пройти регистрацию', ['register.firstName']);
    }
}
