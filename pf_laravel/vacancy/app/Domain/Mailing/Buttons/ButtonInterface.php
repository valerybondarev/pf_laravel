<?php

namespace App\Domain\Mailing\Buttons;

use App\Bot\Entities\Keyboard;
use App\Domain\Mailing\Entities\MailingButton;

interface ButtonInterface
{
    public function addToKeyboard(Keyboard $keyboard, MailingButton $button);
}