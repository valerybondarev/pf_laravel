<?php


namespace App\Bot\Logic\Main\Keyboard;

use App\Bot\Base\BaseKeyboard;
use App\Bot\Store\UserState;
use App\Domain\Client\Enums\ClientStatusLearnEnum;
use App\Domain\Client\Repositories\SportsCategoryRepository;
use App\Domain\University\Repositories\UniversityRepository;
use App\Domain\TourClub\Repositories\TourClubRepository;

class ClientKeyboard extends BaseKeyboard
{
    public function __construct(
        private TourClubRepository $tourClubRepository,
        private SportsCategoryRepository $sportsCategoryRepository,
        private UniversityRepository $universityRepository,
    )
    {
    }

    public function main(UserState $userState)
    {
        $this->addInlineBtn($userState, '✏ Имя', ['client.changeFirstName'], false);
        $this->addInlineBtn($userState, '✏ Фамилия', ['client.changeLastName'], false);
        $this->addInlineBtn($userState, '✏ Отчество', ['client.changeMiddleName']);
        $this->addInlineBtn($userState, '✏ Телефон', ['client.changePhone'], false);
        $this->addInlineBtn($userState, '✏ Турклуб', ['client.tourClubList'], false);
        $this->addInlineBtn($userState, '✏ Разряд', ['client.sportCategoryList']);
        $this->addInlineBtn($userState, '✏ Место учебы/работы', ['client.statusLearnList']);
        if ($userState->user->status_learn == ClientStatusLearnEnum::LEARN) {
            $this->addInlineBtn($userState, '✏ Факультет', ['client.department', false]);
            $this->addInlineBtn($userState, '✏ Группа', ['client.group']);
        }
        $this->addInlineBtn($userState, '✏ Ссылка на страницу Вк', ['client.changeVkLink']);

        $this->addInlineBtn($userState, 'Подписка на события' . ($userState->user->mailing_events ? ' ✅' : ' ⛔️'), ['client.mailingEvents', (int)!$userState->user->mailing_events]);
        $this->addInlineBtn($userState, 'Подписка на новости' . ($userState->user->mailing_news ? ' ✅' : ' ⛔️'), ['client.mailingNews', (int)!$userState->user->mailing_news]);
    }

    public function tourClubList(UserState $userState)
    {
        $tourClubs = $this->tourClubRepository->all();
        $this->addInlineBtn($userState, 'Назад', ['client.main']);
        foreach ($tourClubs as $tourClub) {
            $this->addInlineBtn($userState, $tourClub->title, ['client.changeTourClub', $tourClub->id]);
        }
    }

    public function sportCategoryList(UserState $userState)
    {
        $sportsCategories = $this->sportsCategoryRepository->allActive();
        $this->addInlineBtn($userState, 'Назад', ['client.main']);
        foreach ($sportsCategories as $sportsCategory) {
            $this->addInlineBtn($userState, $sportsCategory->title, ['client.changeSportCategory', $sportsCategory->id]);
        }
    }

    public function statusLearnList(UserState $userState)
    {
        $this->addInlineBtn($userState, 'Назад', ['client.main']);
        $this->addInlineBtn($userState, 'Учусь', ['client.changeStatusLearn', 1]);
        $this->addInlineBtn($userState, 'Работаю', ['client.changeStatusLearn', 0]);
    }

    public function universitiesList(UserState $userState)
    {
        $universities = $this->universityRepository->allActive();
        $this->addInlineBtn($userState, 'Назад', ['client.statusLearnList']);
        foreach ($universities as $university) {
            $this->addInlineBtn($userState, $university->title, ['client.selectUniversity', $university->id]);
        }
    }

    public function welcome(UserState $userState)
    {
        $this->addInlineBtn($userState, 'Пройти регистрацию', ['register.firstName']);
    }

    public function backToClientMain(UserState $userState)
    {
        $this->addInlineBtn($userState, 'Отмена', ['client.main']);
    }
}
