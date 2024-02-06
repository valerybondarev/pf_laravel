<?php

namespace App\Domain\Client\Services;


use App\Base\Services\BaseService;
use App\Domain\Client\Entities\Client;
use App\Domain\Client\Entities\ClientMessage;
use App\Base\Interfaces\ManageServiceInterface;
use App\Domain\Client\Enums\ClientMessageStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use tsvetkov\telegram_bot\exceptions\BadRequestException;
use tsvetkov\telegram_bot\exceptions\ForbiddenException;
use tsvetkov\telegram_bot\exceptions\InvalidTokenException;
use tsvetkov\telegram_bot\TelegramBot;

/**
 * This is the service class for table "client_messages".
 * Class App\Domain\Client\Services\ClientMessageService
 *
 * @package  App\Domain\Client\Services
 * @method ClientMessage|null findActive(array $params = [])
 */
class ClientMessageService extends BaseService implements ManageServiceInterface
{
    public function create(array $data): ClientMessage
    {
        $model = new ClientMessage();
        return $this->update($model, $data);
    }

    public function update(ClientMessage|Model $model, array $data): ClientMessage
    {
        $model->client_id = Arr::get($data, 'client_id', $model->client_id);
        $model->text = Arr::get($data, 'text', $model->text);
        $model->is_admin = Arr::get($data, 'is_admin', $model->is_admin);
        $model->status = Arr::get($data, 'status', $model->status);

        $model->saveOrFail();

        return $model;
    }

    public function destroy(ClientMessage|Model $model): bool
    {
        return $model->forceFill(['status' => 'deleted'])->save();
    }

    /**
     * @throws BadRequestException
     * @throws \Throwable
     * @throws ForbiddenException
     * @throws InvalidTokenException
     */
    public function sendTelegram($text, Client $client, $isAdmin = true)
    {
        $bot = new TelegramBot(config('bot.token'));
        $text = strip_tags($text, '<code><b><i><a>');//разрешаем только некоторые теги
        $text = str_replace("\r\n", "\n", $text);
        $bot->sendMessage($client->telegram_id, $text, 'html');
        $model = new ClientMessage();
        $model->client_id = $client->id;
        $model->text = $text;
        $model->is_admin = true;
        $model->status = ClientMessageStatusEnum::READ;
        $model->saveOrFail();
        return $model;
    }
}
