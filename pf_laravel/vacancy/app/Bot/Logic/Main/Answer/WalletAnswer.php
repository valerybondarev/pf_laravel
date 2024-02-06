<?php

namespace App\Bot\Logic\Main\Answer;

use App\Bot\Base\BaseAnswer;
use App\Bot\Logic\Main\Keyboard\WalletKeyboard;
use App\Bot\Store\UserState;
use Validator;

/**
 * Class MainAnswer
 *
 * @package app\bot\core\main\answer
 * @property WalletKeyboard $keyboard
 */
class WalletAnswer extends BaseAnswer
{
    public function __construct(private UserState $userState, private WalletKeyboard $keyboard)
    {
    }

    public function main()
    {
        if ($this->userState->user->metamask) {
            $this->userState->textBuilder()->write("🐻Your current Poligon wallet: {$this->userState->user->metamask}");
            $this->keyboard->main($this->userState);
        } else {
            $this->userState->textBuilder()
                ->write('Type the address of the Poligon Matic wallet (прим. MetaMask).')
                ->writeLn('📍ATTENTION: Stock-exchange and non-castodial are not appropriate.')
                ->newLine(2)
                ->write('🐻Your current Poligon address: is not specified');
            $this->userState->user->setCommand(['wallet.write']);
        }
        $this->userState->sendMessage();
    }

    public function change()
    {
        $this->userState->textBuilder()->write('Send a new Poligon wallet');
        $this->userState->user->setCommand(['wallet.write']);
        $this->userState->sendMessage();
    }

    public function write()
    {
        if ($this->validateUrl($this->userState->message->text)) {
            $this->userState->user->metamask = $this->userState->message->text;
            $this->userState->user->saveOrFail();
            $this->userState->textBuilder()->write('Your new wallet is saved 💥');
            $this->userState->goToMain();
        } else {
            $this->userState->textBuilder()
                ->write('Oops! Something went wrong. 😔 Make sure that this is the address of the Poligon Matic wallet.');
            $this->userState->sendMessage();
        }
    }

    private function validateUrl($url): bool
    {
        $validator = Validator::make(['url' => $url], [
            'url' => 'required|website',
        ]);
        return !$validator->fails();
    }
}
