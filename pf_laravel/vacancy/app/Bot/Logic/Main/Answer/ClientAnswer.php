<?php

namespace App\Bot\Logic\Main\Answer;

use App\Bot\Base\BaseAnswer;
use App\Bot\Helpers\DebugTnHelper;
use App\Bot\Helpers\ValidateHelper;
use App\Bot\Logic\Main\Keyboard\ClientKeyboard;
use App\Bot\Logic\Main\Text\ClientText;
use App\Bot\Store\UserState;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Throwable;

/**
 * Class MainAnswer
 *
 * @package app\bot\core\main\answer
 * @property ClientKeyboard $keyboard
 */
class ClientAnswer extends BaseAnswer
{
    use ValidateHelper;

    public function __construct(
        private UserState $userState,
        private ClientKeyboard $keyboard,
    )
    {
    }

    public function main()
    {
        $this->userState->textBuilder()->write('<b>Данные:</b>');
        $this->userState->textBuilder()->writeLn(ClientText::clientInfo($this->userState->user));
        $this->userState->user->setCommand(['main.main']);
        $this->keyboard->main($this->userState);
        $this->userState->sendMessage(true);
    }

    /**
     * @throws Throwable
     */
    public function tourClubList()
    {
        $this->userState->textBuilder()->write('Из какого вы турклуба? Напишите @denzlbro если вашего турклуба нет в списке');
        $this->keyboard->tourClubList($this->userState);
        $this->userState->sendMessage();
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function changeTourClub()
    {
        $this->userState->user->tour_club_id = $this->userState->inlineCommand[1];
        $this->userState->user->saveOrFail();
        $this->yearInTk();
    }

    /**
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function yearInTk()
    {
        if ($this->userState->inlineMode) {
            $this->userState->textBuilder()->write('Напишите год, с которого вы в турклубе');
            $this->userState->user->setCommand(['client.yearInTk']);
            $this->userState->inlineMode = true;
            $this->userState->sendMessage();
        } else {
            $this->userState->user->year_in_tk = $this->userState->message->text;
            $this->userState->user->saveOrFail();
            $this->main();
        }
    }

    /**
     * @throws Throwable
     */
    public function sportCategoryList()
    {
        $this->userState->textBuilder()->write('Какой текущий защищенный разряд у вас? Или может есть звание?');
        $this->keyboard->sportCategoryList($this->userState);
        $this->userState->sendMessage();
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function changeSportCategory()
    {
        $this->userState->user->sports_category_id = $this->userState->inlineCommand[1];
        $this->userState->user->saveOrFail();
        $this->main();
    }

    /**
     * @throws Throwable
     */
    public function statusLearnList()
    {
        $this->userState->textBuilder()->write('Вы учитесь или работаете?');
        $this->keyboard->statusLearnList($this->userState);
        $this->userState->sendMessage();
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function changeStatusLearn()
    {
        $this->userState->user->status_learn = $this->userState->inlineCommand[1];
        $this->userState->user->saveOrFail();
        $this->userState->inlineMode = true;
        if ($this->userState->user->status_learn) {
            $this->userState->inlineMode = false;
            $this->universitiesList();
        } else {
            $this->workOrganization();
        }
    }

    /**
     * @throws Throwable
     */
    public function universitiesList()
    {
        $this->userState->textBuilder()->write('Напишите название вашего университета');
        $this->userState->inlineMode = true;
        $this->keyboard->universitiesList($this->userState);
        $this->userState->sendMessage();
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function selectUniversity()
    {
        $this->userState->user->university_id = $this->userState->inlineCommand[1];
        $this->userState->user->saveOrFail();
        $this->yearInUniversity();
    }

    /**
     * @throws Throwable
     */
    public function yearInUniversity()
    {
        $this->userState->textBuilder()->write('Напишите год поступления в учебное заведение');
        $this->userState->user->setCommand(['client.changeYearInUniversity']);
        $this->userState->inlineMode = true;
        $this->userState->sendMessage();
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function changeYearInUniversity()
    {
        $this->userState->user->year_in_university = $this->userState->message->text;
        $this->userState->user->saveOrFail();
        $this->department();
    }

    /**
     * @throws Throwable
     */
    public function department()
    {
        $changeDepartmentWithGroup = $this->userState->inlineCommand[1] ?? true;
        $this->userState->inlineMode = false;
        $this->userState->textBuilder()->write('Напишите название вашего факультета');
        $this->userState->user->setCommand(['client.changeDepartment', $changeDepartmentWithGroup]);
        $this->userState->inlineMode = false;
        $this->userState->sendMessage();
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function changeDepartment()
    {
        $changeDepartmentWithGroup = $this->userState->inlineCommand[1] ?? true;
        $this->userState->user->department = $this->userState->message->text;
        $this->userState->user->saveOrFail();
        if ($changeDepartmentWithGroup) {
            $this->group();
        }
    }

    /**
     * @throws Throwable
     */
    public function group()
    {
        $this->userState->inlineMode = false;
        $this->userState->textBuilder()->write('Напишите название вашей группы в университете');
        $this->userState->user->setCommand(['client.changeGroup']);
        $this->userState->inlineMode = false;
        $this->userState->sendMessage();
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function changeGroup()
    {
        $this->userState->user->group = $this->userState->message->text;
        $this->userState->user->saveOrFail();
        $this->userState->user->setCommand(['main.main']);
        $this->main();
    }

    /**
     * @throws Throwable
     */
    public function workOrganization()
    {
        $this->userState->inlineMode = false;
        $this->userState->textBuilder()->write('Напишите название вашей организации');
        $this->userState->user->setCommand(['client.changeWorkOrganization']);
        $this->userState->inlineMode = false;
        $this->userState->sendMessage();
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function changeWorkOrganization()
    {
        $this->userState->user->work_organization = $this->userState->message->text;
        $this->userState->user->saveOrFail();
        $this->main();
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function changeVkLink()
    {
        if ($this->userState->inlineMode) {
            $this->userState->textBuilder()->write('Напишите ссылку на свою страницу Вконтакте');
            $this->userState->user->setCommand(['client.changeVkLink']);
            $this->keyboard->backToClientMain($this->userState);
            $this->userState->sendMessage();
        } else {
            $this->userState->user->vk_link = $this->userState->message->text;
            $this->userState->user->saveOrFail();
            $this->main();
        }
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function changeFirstName()
    {
        if ($this->userState->inlineMode) {
            $this->userState->textBuilder()->write('Напишите ваше имя');
            $this->userState->user->setCommand(['client.changeFirstName']);
            $this->keyboard->backToClientMain($this->userState);
            $this->userState->sendMessage();
        } else {
            $this->userState->user->first_name = $this->userState->message->text;
            $this->userState->user->saveOrFail();
            $this->main();
        }
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function changeLastName()
    {
        if ($this->userState->inlineMode) {
            $this->userState->textBuilder()->write('Напишите вашу фамилию');
            $this->userState->user->setCommand(['client.changeLastName']);
            $this->keyboard->backToClientMain($this->userState);
            $this->userState->sendMessage();
        } else {
            $this->userState->user->last_name = $this->userState->message->text;
            $this->userState->user->saveOrFail();
            $this->main();
        }
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function changeMiddleName()
    {
        if ($this->userState->inlineMode) {
            $this->userState->textBuilder()->write('Напишите ваше отчество');
            $this->userState->user->setCommand(['client.changeMiddleName']);
            $this->keyboard->backToClientMain($this->userState);
            $this->userState->sendMessage();
        } else {
            $this->userState->user->middle_name = $this->userState->message->text;
            $this->userState->user->saveOrFail();
            $this->main();
        }
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function changePhone()
    {
        if ($this->userState->inlineMode) {
            $this->userState->textBuilder()->write('Напишите Ваш номер телефона через 8. Пример: 89991234567');
            $this->userState->user->setCommand(['client.changePhone']);
            $this->keyboard->backToClientMain($this->userState);
            $this->userState->sendMessage();
        } else {
            /** Фильтры на правильность значений начало */
            try {
                $value = $this->validatePhone($this->userState);
                $this->userState->user->phone = $value;
                $this->userState->user->saveOrFail();
                $this->main();
            } catch (Exception $e) {
                DebugTnHelper::exception($e);
                return;
            }
            /** Фильтры на правильность значений конец */
        }
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function mailingEvents()
    {
        if ($this->userState->inlineCommand[1] == $this->userState->user->mailing_events) {
            $this->userState->answerCallbackQuery('Вы уже подписаны на события!');
            return;
        }
        $this->userState->user->mailing_events = $this->userState->inlineCommand[1];
        $this->userState->user->saveOrFail();
        $this->main();
    }

    /**
     * @throws Throwable
     * @throws BindingResolutionException
     */
    public function mailingNews()
    {
        if ($this->userState->inlineCommand[1] == $this->userState->user->mailing_news) {
            $this->userState->answerCallbackQuery('Вы уже подписаны на новости!');
            return;
        }
        $this->userState->user->mailing_news = $this->userState->inlineCommand[1];
        $this->userState->user->saveOrFail();
        $this->main();
    }
}
