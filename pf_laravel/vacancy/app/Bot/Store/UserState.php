<?php

namespace App\Bot\Store;

use App\Bot\Helpers\DebugTnHelper;
use App\Bot\Helpers\UserStateTextBuilder;
use App\Bot\Logic\Main\Answer\MainAnswer;
use App\Domain\Client\Entities\Client;
use App\Domain\Client\Services\ClientService;
use Illuminate\Contracts\Container\BindingResolutionException;
use stdClass;
use tsvetkov\telegram_bot\entities\callback\CallbackQuery;
use tsvetkov\telegram_bot\entities\message\Message;
use tsvetkov\telegram_bot\entities\update\Update;
use tsvetkov\telegram_bot\TelegramBot;

class UserState
{
    public $bot;
    /**
     * @var Message|CallbackQuery|stdClass|null
     */
    public $message;
    public $messageText     = '';
    public $messageDocument = '';
    public $keyboard        = null;
    public $keyboardRow     = 0;
    public $keyboardButtons = [];
    /**
     * @var Client|null
     */
    public $user;
    public $inlineCommand;
    public $inlineAction;
    public $inlineController;
    public $pressedButton = [];
    public $pressedState  = false;
    public $inlineMode    = false;
    public $isNew         = false;

    public function __construct(Update|stdClass $update)
    {
        $this->bot = new TelegramBot(config('bot.token'));
        $this->inlineMode = false;
        //if (!isset($update->message->from) || !isset($update->callback_query->from)) {
        //    DebugTnHelper::write($update);
        //}
        if (isset($update->callback_query)) {
            $this->inlineMode = true;
            $fromId = $update->callback_query->from->id;
            $username = $update->callback_query->from->username;
            $this->message = $update->callback_query;
        } else {
            $fromId = $update->message->from->id;
            $username = $update->message->from->username;
            $this->message = $update->message;
        }
        if (!$this->user = Client::query()->where('telegram_id', $fromId)->first()) {
            $data = [
                'telegramId' => $fromId,
                'username'   => $username,
            ];
            $this->user = app(ClientService::class)->create($data);
            $this->isNew = true;
        }
        //$this->user = Users::find()->where(['telegram_id' => $fromId])->one();
        if ($this->user && !$this->inlineMode) {
            $this->activateUserCommand();
        } elseif ($this->inlineMode) {
            $this->activateInlineCommand($update->callback_query->data);
        }
    }

    public function sendMessage($disableWebPagePreview = null): Message|bool|null
    {
        if ($this->messageText) {
            $this->messageText = strip_tags($this->messageText, '<code><b><i><a>');//разрешаем только некоторые теги
            $this->messageText = str_replace("\r\n", "\n", $this->messageText);
            $this->messageText = htmlspecialchars_decode($this->messageText);
            $this->messageText = html_entity_decode($this->messageText);
            if ((!$this->inlineMode || ($this->keyboard && key($this->keyboard) == 'keyboard')) || config('app.env') == 'local') {
                $r = $this->bot->sendMessage(
                    $this->user->telegram_id,
                    $this->messageText,
                    'HTML',
                    $this->keyboard,
                    $disableWebPagePreview
                );
            } else {
                $r = $this->bot->editMessageText(
                    $this->messageText,
                    $this->user->telegram_id,
                    $this->message->message->message_id,
                    null,
                    'HTML',
                    $disableWebPagePreview,
                    $this->keyboard
                );
            }
            if ($this->keyboard && config('app.local')) {
                DebugTnHelper::write($this->keyboard, 433869407, 1);
            }
            return $r;
        }
        return null;
    }

    public function sendDocument()
    {
        if ($this->messageDocument) {
            $this->bot->sendDocument(
                $this->message->chat->id,
                \Yii::getAlias('@app/web') . $this->messageDocument,
                null,
                $this->messageText,
            );
        }
    }

    public function activateUserCommand($command = null)
    {
        if ($command = json_decode($command ?? $this->user->command, true)) {
            [$this->inlineController, $this->inlineAction] = explode('.', $command[0]);
            $this->inlineCommand = $command;
        }
    }

    public function activateInlineCommand($command)
    {
        if (is_array($command) || $command = json_decode($command, true)) {
            [$this->inlineController, $this->inlineAction] = explode('.', $command[0]);
            $this->inlineCommand = $command;
        }
    }

    /**
     * Важный момент! При вызове этой функции и если messageText уже заполнен, то он не будет заменен. УЧТИТЕ ЭТО!
     *
     * @throws BindingResolutionException
     */
    public function goToMain($question = true): void
    {
        $this->keyboardReset();
        $this->user->setCommand(['main.main']);
        MainAnswer::make($this)->main($question);
    }

    /**
     * Аналитика действий пользователя
     *
     * @param string $eventName
     */
    public function createAnalytic(string $eventName = 'click')
    {
        Analytic::addEvent($this->user->id, $eventName);
    }

    public function keyboardReset()
    {
        $this->keyboardButtons = [];
        $this->keyboardRow = 0;
    }

    public function textBuilder(): UserStateTextBuilder
    {
        return UserStateTextBuilder::make($this);
    }

    /**
     * @param $text 0-200 символов
     * @param $showAlert
     * @param $url
     * @param $cacheTime
     *
     * @return void
     * @throws \tsvetkov\telegram_bot\exceptions\BadRequestException
     * @throws \tsvetkov\telegram_bot\exceptions\ForbiddenException
     * @throws \tsvetkov\telegram_bot\exceptions\InvalidTokenException
     */
    public function answerCallbackQuery($text, $showAlert = null, $url = null, $cacheTime = null)
    {
        $this->bot->answerCallbackQuery($this->message->id, $text, $showAlert, $url, $cacheTime);
    }

}
