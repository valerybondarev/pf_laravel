<?php

namespace App\Domain\Client\Services;

use App\Bot\Helpers\DebugTnHelper;
use App\Domain\Client\Enums\ClientTelegramStatusEnum;
use App\Domain\Client\Repositories\ClientRepository;

class ClientTelegramStatusService
{
    public function __construct(private ClientRepository $clientRepository)
    {
    }

    public function changeStatus($postBody): void
    {
        if (!isset($postBody->my_chat_member->from->id)) {
            DebugTnHelper::write("Нет id пользователя при смене статуса");
        }
        if (isset($postBody->my_chat_member->from->id) && isset($postBody->my_chat_member->new_chat_member->status)) {
            $clientNewStatus = $postBody->my_chat_member->new_chat_member->status;
            $fromId = $postBody->my_chat_member->from->id;
            if (in_array($clientNewStatus, ClientTelegramStatusEnum::keys())) {
                $client = $this->clientRepository->find(['telegramId' => $fromId]);
                if ($client) {
                    $client->telegram_status = $clientNewStatus;
                    $client->saveOrFail();
                } else {
                    DebugTnHelper::write("Пользователь с $fromId не найден");
                }
            } else {
                DebugTnHelper::write("Статус пользователя не определен: $clientNewStatus");
            }
        }
    }
}