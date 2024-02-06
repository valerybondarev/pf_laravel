<?php

namespace App\Bot\Logic\Main\Answer;

use App\Bot\Base\BaseAnswer;
use App\Bot\Logic\Main\Keyboard\RegisterKeyboard;
use App\Bot\Logic\Main\Text\ClientText;
use App\Bot\Store\UserState;
use App\Domain\Client\Enums\ClientRoleEnum;
use App\Domain\Client\Enums\ClientStatusEnum;
use App\Domain\Client\Repositories\ClientRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Throwable;
use Validator;

/**
 * Class MainAnswer
 *
 * @package app\bot\core\main\answer
 * @property RegisterKeyboard $keyboard
 */
class RegisterAnswer extends BaseAnswer
{
    public function __construct(
        private UserState $userState,
        private RegisterKeyboard $keyboard,
        private ClientRepository $clientRepository,
    )
    {
    }

    /**
     * Важный момент! При вызове этой функции и если messageText уже заполнен, то он не будет заменен. УЧТИТЕ ЭТО!
     *
     * @return void
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function firstName()
    {
        if ($this->userState->inlineMode) {
            $this->userState->textBuilder()->write('Напишите Ваше имя');
            $this->userState->user->setCommand(['register.firstName']);
            $this->userState->sendMessage();
        } else {
            $this->userState->user->first_name = $this->userState->message->text;
            $this->userState->user->registered_at = null;
            $this->userState->user->saveOrFail();
            $this->userState->inlineMode = true;
            $this->lastName();
        }
    }

    /**
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function lastName()
    {
        if ($this->userState->inlineMode) {
            $this->userState->inlineMode = false;
            $this->userState->textBuilder()->write('Напишите Вашу фамилию');
            $this->userState->user->setCommand(['register.lastName']);
            $this->userState->sendMessage();
        } else {
            $this->userState->user->last_name = $this->userState->message->text;
            $this->userState->user->saveOrFail();
            $this->userState->inlineMode = true;
            $this->middleName();
        }
    }

    /**
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function middleName()
    {
        if ($this->userState->inlineMode) {
            $this->userState->inlineMode = false;
            $this->userState->textBuilder()->write('Напишите Ваше отчество');
            $this->userState->user->setCommand(['register.middleName']);
            $this->userState->sendMessage();
        } else {
            $this->userState->user->middle_name = $this->userState->message->text;
            $this->userState->user->saveOrFail();
            $this->userState->inlineMode = true;
            //$this->registerEnd();
            $this->bornAt();
        }
    }

    /**
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function bornAt()
    {
        if ($this->userState->inlineMode) {
            $this->userState->inlineMode = false;
            $this->userState->textBuilder()->write('Напишите дату рождения');
            $this->userState->user->setCommand(['register.bornAt']);
            $this->userState->sendMessage();
        } else {
            $validator = Validator::make(['input' => $this->userState->message->text], [
                'input' => 'required|date',
            ]);

            if ($validator->fails()) {
                $this->userState->textBuilder()->write('Введите корректное дату рождения в формате ДД.ММ.ГГГГ');
                $this->userState->sendMessage();
                return;
            }
            $this->userState->user->born_at = Carbon::make($this->userState->message->text)->format('Y-m-d');
            $this->userState->user->saveOrFail();
            $this->userState->inlineMode = true;
            $this->phone();
        }
    }

    /**
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function phone()
    {
        if ($this->userState->inlineMode) {
            $this->userState->inlineMode = false;
            $this->userState->textBuilder()->write('Напишите телефон в формате 89ХХ1234567');
            $this->userState->user->setCommand(['register.phone']);
            $this->userState->sendMessage();
        } else {
            $this->userState->user->phone = $this->userState->message->text;
            $this->userState->user->saveOrFail();
            $this->selectTourClub();
        }
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function selectTourClub()
    {
        if ($this->userState->inlineMode) {
            $this->userState->user->tour_club_id = $this->userState->inlineCommand[1];
            $this->userState->user->saveOrFail();
            $this->userState->inlineMode = true;
            $this->yearInTk();
        } else {
            $this->userState->textBuilder()->write('Из какого вы турклуба? Напишите @denzlbro если вашего турклуба нет в списке');
            $this->keyboard->selectTourClub($this->userState);
            $this->userState->sendMessage();
        }
    }

    /**
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function yearInTk()
    {
        if ($this->userState->inlineMode) {
            $this->userState->inlineMode = false;
            $this->userState->textBuilder()->write('Напишите год, с которого вы в турклубе');
            $this->userState->user->setCommand(['register.yearInTk']);
            $this->userState->sendMessage();
        } else {
            $this->userState->user->year_in_tk = $this->userState->message->text;
            $this->userState->user->saveOrFail();
            $this->userState->inlineMode = false;
            $this->selectSportCategory();
        }
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function selectSportCategory()
    {
        if ($this->userState->inlineMode) {
            $this->userState->user->sports_category_id = $this->userState->inlineCommand[1];
            $this->userState->user->saveOrFail();
            $this->userState->inlineMode = false;
            $this->selectStatusLearn();
        } else {
            $this->userState->textBuilder()->write('Какой текущий защищенный разряд у вас? Или может есть звание?');
            $this->keyboard->selectSportCategory($this->userState);
            $this->userState->sendMessage();
        }
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function selectStatusLearn()
    {
        if ($this->userState->inlineMode) {
            $this->userState->user->status_learn = $this->userState->inlineCommand[1];
            $this->userState->user->saveOrFail();
            if ($this->userState->user->status_learn) {
                $this->userState->inlineMode = false;
                $this->selectUniversity();
            } else {
                $this->workOrganization();
            }
        } else {
            $this->userState->textBuilder()->write('Вы учитесь или работаете?');
            $this->keyboard->selectStatusLearn($this->userState);
            $this->userState->sendMessage();
        }
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function selectUniversity()
    {
        if ($this->userState->inlineMode) {
            $this->userState->user->university_id = $this->userState->inlineCommand[1];
            $this->userState->user->saveOrFail();
            $this->userState->inlineMode = true;
            $this->yearInUniversity();
        } else {
            $this->userState->textBuilder()->write('Выберите учебное заведение в котором учитесь');
            $this->keyboard->selectUniversity($this->userState);
            $this->userState->sendMessage();
        }
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function yearInUniversity()
    {
        if ($this->userState->inlineMode) {
            $this->userState->inlineMode = false;
            $this->userState->textBuilder()->write('Напишите год поступления в учебное заведение');
            $this->userState->user->setCommand(['register.yearInUniversity']);
            $this->userState->sendMessage();
        } else {
            $this->userState->user->year_in_university = $this->userState->message->text;
            $this->userState->user->saveOrFail();
            $this->userState->inlineMode = true;
            $this->department();
        }
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function department()
    {
        if ($this->userState->inlineMode) {
            $this->userState->inlineMode = false;
            $this->userState->textBuilder()->write('Напишите название вашего факультета. Например: ФАДЭТ, ИАТМ');
            $this->userState->user->setCommand(['register.department']);
            $this->userState->sendMessage();
        } else {
            $this->userState->user->department = $this->userState->message->text;
            $this->userState->user->saveOrFail();
            $this->userState->inlineMode = true;
            $this->group();
        }
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function group()
    {
        if ($this->userState->inlineMode) {
            $this->userState->inlineMode = false;
            $this->userState->textBuilder()->write('Напишите название вашей группы в университете заглавными буквами. Например ГМ-542');
            $this->userState->user->setCommand(['register.group']);
            $this->userState->sendMessage();
        } else {
            $this->userState->user->group = $this->userState->message->text;
            $this->userState->user->saveOrFail();
            $this->liveInDorm();
        }
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function liveInDorm()
    {
        if ($this->userState->inlineMode) {
            $this->userState->user->live_in_dorm = $this->userState->inlineCommand[1];
            $this->userState->user->saveOrFail();
            $this->userState->inlineMode = true;
            $this->vkLink();
        } else {
            $this->userState->textBuilder()->write('Живете в общежитие учебного заведения?');
            $this->userState->user->setCommand(['register.liveInDorm']);
            $this->keyboard->liveInDorm($this->userState);
            $this->userState->sendMessage();
        }
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function workOrganization()
    {
        if ($this->userState->inlineMode) {
            $this->userState->inlineMode = false;
            $this->userState->textBuilder()->write('Напишите название вашей организации');
            $this->userState->user->setCommand(['register.workOrganization']);
            $this->userState->sendMessage();
        } else {
            $this->userState->user->work_organization = $this->userState->message->text;
            $this->userState->user->saveOrFail();
            $this->userState->inlineMode = true;
            $this->vkLink();
        }
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function vkLink()
    {
        if ($this->userState->inlineMode) {
            $this->userState->textBuilder()->write('Напишите ссылку на свою страницу Вконтакте.');
            $this->userState->textBuilder()->write('Например: https://vk.com/denzlbro');
            $this->userState->textBuilder()->writeLn('Пример для копирования выслали следующим сообщением');
            $this->userState->textBuilder()->writeLn('Можете также вставить сообщением Я ВКонтакте: vk.com/denzlbro');
            $this->userState->user->setCommand(['register.vkLink']);
            $this->userState->inlineMode = false;
            $this->userState->sendMessage();
            $this->userState->textBuilder()->write('<a href="https://ikar.chatway.ru/admin/static/bot/instruction_link.jpg">ᅠ</a>'.'https://vk.com/', true);//
            $this->userState->sendMessage();
        } else {
            $url = str_replace('Я ВКонтакте: ','https://', $this->userState->message->text);
            if ($this->validateUrl($url)) {
                $this->userState->user->vk_link = $url;
                $this->userState->user->saveOrFail();
                $this->registerEnd();
            } else {
                $this->userState->textBuilder()->write('Ссылка не по формату((');
                $this->userState->textBuilder()->writeLn('Можете также вставить сообщением');
                $this->userState->textBuilder()->writeLn('<code>Я ВКонтакте: vk.com/denzlbro</code>');
                $this->userState->textBuilder()->write('<a href="https://ikar.chatway.ru/admin/static/bot/instruction_link.jpg">ᅠ</a>');//
                $this->keyboard->deleteKeyboard($this->userState);
                $this->userState->sendMessage();
            }
        }
    }

    /**
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function registerEnd()
    {
        $this->userState->user->registered_at = Carbon::now();
        $this->userState->user->saveOrFail();
        $this->userState->textBuilder()->write('Регистрация прошла успешно! Приятного пользования!');
        $this->userState->goToMain(false);
        $this->userState->keyboardReset();
        $this->userState->textBuilder()->write('Новый пользователь', true);
        $this->userState->textBuilder()->writeLn(ClientText::clientInfo($this->userState->user));
        $this->keyboard->registerEnd($this->userState);
        $moderators = $this->clientRepository->getByRole(ClientRoleEnum::MODERATOR);
        foreach ($moderators as $moderator) {
            $this->userState->user = $moderator;
            $this->userState->sendMessage();
        }
    }

    public function verified()
    {
        $moderatorClient = $this->userState->user;
        $client = $this->clientRepository->one($this->userState->inlineCommand[1]);
        $client->status = $this->userState->inlineCommand[2] ? ClientStatusEnum::VERIFIED : ClientStatusEnum::ACTIVE;
        $client->saveOrFail();
        $this->userState->user = $client;
        $this->userState->textBuilder()->write('Новый пользователь');
        $this->userState->textBuilder()->writeLn(ClientText::clientInfo($this->userState->user));
        $this->userState->textBuilder()->writeLn(Carbon::now()->timestamp);
        $this->keyboard->registerEnd($this->userState);
        $this->userState->user = $moderatorClient;
        $this->userState->sendMessage();
    }

    public function verifiedStatus()
    {
        $moderatorClient = $this->userState->user;
        $client = $this->clientRepository->one($this->userState->inlineCommand[1]);
        $this->userState->user = $client;
        $this->userState->textBuilder()->write('Новый пользователь');
        $this->userState->textBuilder()->writeLn(ClientText::clientInfo($this->userState->user));
        $this->userState->textBuilder()->writeLn(Carbon::now()->timestamp);
        $this->keyboard->registerStatus($this->userState);
        $this->userState->user = $moderatorClient;
        $this->userState->sendMessage();
    }

    public function verifiedSend()
    {
        $this->userState->inlineMode = false;
        $moderatorClient = $this->userState->user;
        $client = $this->clientRepository->one($this->userState->inlineCommand[1]);
        $this->userState->user = $client;
        $this->userState->textBuilder()->write('Поздравляем! Ваш статус изменен на верифицированный!');
        $this->userState->sendMessage();
        $this->userState->user = $moderatorClient;
        $this->userState->answerCallbackQuery('Статус успешно отправлен!', true);
    }


    private function validateUrl($url): bool
    {
        $validator = Validator::make(['url' => $url], [
            'url' => 'required|website',
        ]);
        return !$validator->fails();
    }
}
