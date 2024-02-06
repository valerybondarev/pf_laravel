<?php


namespace App\Bot\Logic\Main\Keyboard;

use App\Bot\Base\BaseKeyboard;
use App\Bot\Store\UserState;
use App\Domain\Client\Enums\ClientStatusEnum;
use App\Domain\Client\Repositories\SportsCategoryRepository;
use App\Domain\University\Repositories\UniversityRepository;
use App\Domain\TourClub\Repositories\TourClubRepository;
use Carbon\Carbon;

class RegisterKeyboard extends BaseKeyboard
{
    public function __construct(
        private TourClubRepository $tourClubRepository,
        private SportsCategoryRepository $sportsCategoryRepository,
        private UniversityRepository $universityRepository,
    )
    {
    }

    public function main()
    {

    }

    public function selectTourClub(UserState $userState)
    {
        $tourClubs = $this->tourClubRepository->all();
        foreach ($tourClubs as $tourClub) {
            $this->addInlineBtn($userState, $tourClub->title, ['register.selectTourClub', $tourClub->id]);
        }
    }

    public function selectSportCategory(UserState $userState)
    {
        $sportsCategories = $this->sportsCategoryRepository->allActive();
        foreach ($sportsCategories as $sportsCategory) {
            $this->addInlineBtn($userState, $sportsCategory->title, ['register.selectSportCategory', $sportsCategory->id]);
        }
    }

    public function selectStatusLearn(UserState $userState)
    {
        $this->addInlineBtn($userState, 'Учусь', ['register.selectStatusLearn', 1]);
        $this->addInlineBtn($userState, 'Работаю', ['register.selectStatusLearn', 0]);
    }

    public function liveInDorm(UserState $userState)
    {
        $this->addInlineBtn($userState, 'Да', ['register.liveInDorm', 1]);
        $this->addInlineBtn($userState, 'Нет', ['register.liveInDorm', 0]);
    }

    public function selectUniversity(UserState $userState)
    {
        $universities = $this->universityRepository->allActive();
        foreach ($universities as $university) {
            $this->addInlineBtn($userState, $university->title, ['register.selectUniversity', $university->id]);
        }
    }

    public function registerEnd(UserState $userState)
    {
        $verified = $userState->user->status == ClientStatusEnum::VERIFIED;
        $this->addInlineBtn($userState, 'Верифицированный ' . ($verified ? '✅' : '❌'), ['register.verified', $userState->user->id, (int)!$verified]);
        $this->addInlineBtn($userState, 'Обновить ' . Carbon::now()->format('H:i:s'), ['register.verifiedStatus', $userState->user->id]);
        $this->addInlineBtn($userState, 'Отправить сообщение о верификации', ['register.verifiedSend', $userState->user->id]);
        $this->addInlineBtn($userState, 'Отправить сообщение', ['main.sendMessageInit', $userState->user->id]);
    }

    public function registerStatus(UserState $userState)
    {
        $verified = $userState->user->status == ClientStatusEnum::VERIFIED;
        $this->addInlineBtn($userState, 'Верифицированный ' . ($verified ? '✅' : '❌'), ['register.verified', $userState->user->id, (int)!$verified]);
        $this->addInlineBtn($userState, 'Обновить ' . Carbon::now()->format('H:i:s'), ['register.verifiedStatus', $userState->user->id]);
        $this->addInlineBtn($userState, 'Отправить сообщение о верификации', ['register.verifiedSend', $userState->user->id]);
        $this->addInlineBtn($userState, 'Отправить сообщение', ['main.sendMessageInit', $userState->user->id]);
    }
}
