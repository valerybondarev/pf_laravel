<?php


namespace App\Bot\Logic\Main\Keyboard;

use App\Bot\Base\BaseKeyboard;
use App\Bot\Store\UserState;

class WalletKeyboard extends BaseKeyboard
{
    public function __construct()
    {
    }

    public function main(UserState $userState)
    {
        $this->addInlineBtn($userState, 'Change', ['wallet.change']);
    }
}
