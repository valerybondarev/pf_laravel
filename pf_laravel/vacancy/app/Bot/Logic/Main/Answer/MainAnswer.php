<?php

namespace App\Bot\Logic\Main\Answer;

use App\Bot\Base\BaseAnswer;
use App\Bot\Helpers\DebugTnHelper;
use App\Bot\Logic\Main\Keyboard\MainKeyboard;
use App\Bot\Store\UserState;
use App\Domain\Client\Enums\ClientRoleEnum;
use App\Domain\Client\Repositories\ClientRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use tsvetkov\telegram_bot\exceptions\BadRequestException;
use tsvetkov\telegram_bot\exceptions\ForbiddenException;
use tsvetkov\telegram_bot\exceptions\InvalidTokenException;

/**
 * Class MainAnswer
 *
 * @package app\bot\core\main\answer
 * @property MainKeyboard $keyboard
 */
class MainAnswer extends BaseAnswer
{
    public function __construct(
        private UserState $userState,
        private MainKeyboard $keyboard,
        private ClientRepository $clientRepository,
    )
    {
    }

    /**
     * Важный момент! При вызове этой функции и если messageText уже заполнен, то он не будет заменен. УЧТИТЕ ЭТО!
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function main($question = true)
    {
        if (!$this->userState->user->registered_at) {
            $this->welcome();
        } else {
            $oldAnswer = $this->userState->user->command;
            $this->userState->user->setCommand(['main.main']);
            $this->keyboard->main($this->userState);
            if ($oldAnswer == json_encode(['main.main']) && !$this->keyboard->checkPush($this->userState) && $question) {
                $this->question();
            } else {
                if (!isset($this->userState->message->text) || !$this->keyboard->isPush($this->userState)) {
                    if (!$this->userState->messageText) {
                        $this->userState->textBuilder()->write('Вы в главном меню!');
                    }
                    $this->userState->sendMessage();
                }
            }

        }
    }

    public function question()
    {
        $this->keyboard->resetKeyboard($this->userState);
        $client = $this->userState->user;
        $moderators = $this->clientRepository->getByRole(ClientRoleEnum::MODERATOR);
        $this->keyboard->question($this->userState);
        foreach ($moderators as $moderator) {
            try {
                $this->userState->bot->forwardMessage($moderator->telegram_id, $client->telegram_id, $this->userState->message->message_id);
            } catch (BadRequestException|ForbiddenException|InvalidTokenException $e) {
                DebugTnHelper::exception($e);
            }
            if ($this->userState->user->username) {
                $text = 'Ник: @' . $this->userState->user->username;
            } else {
                $text = '<a href="tg://user?id=' . $this->userState->user->telegram_id . '">Ссылка на диалог</a>';
            }
            $name = "{$this->userState->user->last_name} {$this->userState->user->first_name} {$this->userState->user->middle_name}";
            $name = trim($name);
            $text .= $name ? PHP_EOL . 'Имя: ' . $name : '';
            $this->userState->textBuilder()->write($text, true);

            $this->userState->user = $moderator;
            $this->userState->sendMessage();
        }
        $this->userState->user = $client;
        $this->keyboard->resetKeyboard($this->userState);
        $this->userState->textBuilder()->write('Мы отправили ваш текст администратору.', true);
        $this->userState->sendMessage();

    }
    public function about()
    {
        $this->userState->textBuilder()->write($this->userState->user->tourClub->about);
        $this->userState->sendMessage();
    }

    public function help()
    {
        $this->userState->textBuilder()->write('Чтобы задать вопрос, необходимо:');
        $this->userState->textBuilder()->writeLn('1. Зайти в главное меню /start.');
        $this->userState->textBuilder()->writeLn('2. Написать вопрос.');
        $this->userState->sendMessage();
    }

    public function welcome()
    {
        $this->userState->user->setCommand(['main.welcome']);
        $this->keyboard->welcome($this->userState);
        $this->userState->textBuilder()->write('Добро пожаловать!');
        if (!$this->userState->user->registered_at) {
            $this->userState->textBuilder()->writeLn('Вам необходимо пройти регистрацию для пользования ботом');
        }
        $this->userState->sendMessage();
    }

    public function membershipState()
    {
        if (!$this->userState->user->membership) {
            $this->userState->textBuilder()->write('В нашем турклубе существует понятие как "членство в турклубе".');
            $this->userState->textBuilder()
                ->writeLn('Вступив в турклуб ты сможешь посещать учебные лекции, тренировки, брать бесплатно тур. оборудование и участвовать в жизни турклуба.');
            $this->userState->textBuilder()
                ->writeLn('Нажми на кнопку вступить в турклуб для того чтобы создать или продлить членство');
            $this->keyboard->membershipState($this->userState);
        } else {
            $this->userState->textBuilder()
                ->write('Членство продлено до ' . $this->userState->user->membership->extended_to->format('d.m.Y'));
        }
        $this->userState->sendMessage();
    }

    public function createMembership()
    {
        $this->userState->user->createMemberShip();
        $this->membershipState();
    }

    public function referralLink()
    {
        $this->userState->textBuilder()
            ->write('Invite your friends and recieve up for every qualified friend 3 TripCoin and increase your chance of winning NFT.')
            ->newLine();
        $invitedClientsCount = count($this->userState->user->invitedClients);
        $this->userState->textBuilder()->p("👥At the moment you have invited $invitedClientsCount participants.");
        $link = "https://t.me/BeaRexBot?start={$this->userState->user->telegram_id}";
        $this->userState->textBuilder()->write("🖇Your personal link for invitations: $link");
        $this->userState->sendMessage();
    }

    public function sendMessageInit()
    {
        $this->userState->user->setCommand(['main.sendMessage', $this->userState->inlineCommand[1]]);
        $this->userState->inlineMode = false;
        $this->userState->textBuilder()->write('Напишите сообщение и мы отправим его пользователю или нажмите /start для отмены');
        $this->userState->sendMessage();
    }

    public function sendMessage()
    {
        $client = $this->clientRepository->one($this->userState->inlineCommand[1]);
        $sender = $this->userState->user;
        $this->userState->user = $client;
        $this->userState->textBuilder()->write($this->userState->message->text);
        $r = $this->userState->sendMessage();
        if (!is_null($r)) {
            $this->userState->user = $sender;
            $this->userState->textBuilder()->write('Ваше сообщение успешно отправлено пользователю', true);
            $this->userState->sendMessage();
            $this->userState->user->setCommand(['main.main']);
        }
    }
}
