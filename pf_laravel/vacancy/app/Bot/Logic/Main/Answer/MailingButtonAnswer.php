<?php

namespace App\Bot\Logic\Main\Answer;

use App\Bot\Base\BaseAnswer;
use App\Bot\Helpers\DebugTnHelper;
use App\Bot\Logic\Main\Keyboard\ClientKeyboard;
use App\Bot\Logic\Main\Text\ClientText;
use App\Bot\Store\UserState;
use App\Domain\Mailing\Enums\MailingButtonListEnum;
use App\Domain\Mailing\Repositories\MailingButtonRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Throwable;

/**
 * Class MainAnswer
 *
 * @package app\bot\core\main\answer
 * @property ClientKeyboard $keyboard
 */
class MailingButtonAnswer extends BaseAnswer
{
    public function __construct(
        private UserState $userState,
        private MailingButtonRepository $mailingButtonRepository,
    )
    {
    }

    public function default()
    {
        $buttonId = $this->userState->inlineCommand[1];
        $mailingButton = $this->mailingButtonRepository->one($buttonId);
        if ($mailingButton) {
            MailingButtonListEnum::relationByAction($mailingButton->action)->handle($this->userState, $mailingButton);
        } else {
            DebugTnHelper::write(['ошибка при нажатии на кнопку рассылки']);
        }
    }
}
