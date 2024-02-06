<?php

namespace App\Bot\Logic\Main\Text;

use App\Domain\Application\Helpers\TextBuilder;
use App\Domain\Client\Entities\Client;
use App\Domain\Client\Enums\ClientStatusEnum;

class ClientText
{
    public static function clientInfo(Client $client): string
    {
        $tb = TextBuilder::make('Имя: <b>' . $client->first_name . '</b>');
        $tb->writeLn('Фамилия: ')->writeBold($client->last_name);
        $tb->writeLn('Отчество: ')->writeBold($client->middle_name);
        $tb->writeLn('Телефон: ')->writeBold($client->phone);
        $tb->writeLn('Турклуб: ')->writeBold($client->tourClub?->title);
        $tb->writeLn('С какого года в турклубе: ')->writeBold($client->year_in_tk);
        $tb->writeLn('Разряд: ')->writeBold($client->sportsCategory?->title);
        if ($client->status_learn) {
            $tb->writeLn('Учитесь: ')->writeBold($client->university?->title);
            $tb->writeLn('Год поступления: ')->writeBold($client->year_in_university);
            $tb->writeLn('Факультет и группа: ')->writeBold("$client->department, $client->group");
        } else {
            $tb->writeLn('Работаете: ')->writeBold($client->work_organization);
        }
        $tb->writeLn('ВК: ')->writeBold($client->vk_link);
        $tb->writeLn('Статус: ' . ($client->status == ClientStatusEnum::VERIFIED ? 'Верифицирован ✅' : 'Неверифицирован ⚠️'));
        return $tb->content();
    }
}
