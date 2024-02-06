<?php

namespace App\Http\Api\Controllers\Bot;

use App;
use App\Bot\Command\MainCommand;
use App\Bot\Helpers\DebugTnHelper;
use App\Bot\Logic\AnswersMap;
use App\Bot\Store\UserState;
use App\Domain\Client\Repositories\SourceRepository;
use App\Domain\Client\Services\ClientTelegramStatusService;
use App\Http\Api\Controllers\Controller;
use app\services\dev\DebugTnService;
use Throwable;
use tsvetkov\telegram_bot\entities\message\Message;
use tsvetkov\telegram_bot\entities\update\Update;
use tsvetkov\telegram_bot\exceptions\BadRequestException;
use tsvetkov\telegram_bot\exceptions\ForbiddenException;
use tsvetkov\telegram_bot\exceptions\InvalidTokenException;

/**
 * This is the controller class for table "cities".
 * Class App\Http\Api\Controllers\Client
 *
 * @package  App\Http\Api\Controllers\Client\CityController
 */
class BotController extends Controller
{
    public function __construct(
        private AnswersMap $answersMap,
        private SourceRepository $sourceRepository,
        private ClientTelegramStatusService $clientTelegramStatusService,
    )
    {
    }

    public function index(): bool
    {
        $data = file_get_contents('php://input');
        $postBody = json_decode($data);
        try {
            $update = App::makeWith(Update::class, ['data' => $postBody]);
            $update->load($postBody);
            /** @var Message $message */
            $message = $update->message;
            if (is_null($message) && !$update->callback_query) {
                /** message and callback_query is null */
                $this->clientTelegramStatusService->changeStatus($postBody);
                DebugTnHelper::write($postBody);
                return true;
            }
            /** @var UserState $userState */
            $userState = App::makeWith(UserState::class, ['update' => $update]);
            if ($userState->isNew || !$userState->user->start) {
                App\Bot\Logic\Main\Answer\MainAnswer::make($userState)->welcome();
                $userState->user->setStart();
                //рефералка
                //if ($userState->isNew) {
                //    if (isset($message->entities) && count($message->entities) > 0) {
                //        foreach ($message->entities as $entity) {
                //            if ($entity->type == 'bot_command'
                //                && substr($message->text, $entity->offset, $entity->length) == '/start'
                //                && count(explode(' ', $message->text)) > 1
                //            ) {//определение является ли ссылка реферальной. Реферальная только тогда когда есть /start
                //                /** @var App\Domain\Client\Entities\Client $invitedBy */
                //                $invitedBy = $this->clientRepository->find(['telegramId' => explode(' ', $message->text)[1]]);
                //                if ($invitedBy && $invitedBy->id != $userState->user->id) {
                //                    $this->invitedClientService->create(['invited_by_id' => $invitedBy->id, 'client_id' => $userState->user->id]);
                //                }
                //                DebugTnHelper::write([$message->text, (bool)$invitedBy]);
                //            }
                //        }
                //    }
                //}
                return true;
            }
            if ($update->callback_query) {
                $this->answersMap->start($userState);
                $userState->user->setStart();
                return true;
            }
            if ($message->text == 'hesoyam') {
                App\Bot\Logic\Main\Answer\RegisterAnswer::make($userState)->registerEnd();
                return true;
            }
            if ($message->text == 'myid') {
                $userState->textBuilder()->write("Ваш id: <code>{$userState->user->telegram_id}</code>");
                $userState->sendMessage();
                return true;
            }
            if ($message) {
                preg_match('/^(?:@\w+\s)?\/([^\s@]+)(@\S+)?\s?(.*)$/', $message->text, $matches);
                $command = $matches[1] ?? null;
                if ($command && $userState->user->registered_at) {
                    /** @var MainCommand $mainCommand */
                    $mainCommand = App::makeWith(MainCommand::class, ['userState' => $userState]);
                    $mainCommand->handle($command);
                    foreach ($message->entities as $entity) {
                        // "entities":[{"offset":0,"length":8,"type":"bot_command"}]
                        if ($entity->type == 'bot_command'
                            && substr($message->text, $entity->offset, $entity->length) == '/start'
                            && count(explode(' ', $message->text)) > 1
                        ) {//Определение является ли ссылка реферальной. Реферальная только тогда когда есть /start
                            $sourceText = str_replace('/start ', '', $message->text);
                            $source = $this->sourceRepository->find(['title' => $sourceText]);
                            if ($source && !$userState->user->source_id) {
                                $userState->user->source_id = $source->id;
                                $userState->user->save();
                                DebugTnHelper::write($sourceText);
                            }
                            break;
                        }
                    }
                } else {
                    if ($message->text && $userState->user->command == json_encode(['main.main']) && $userState->user->telegram_id == 433869407) {
                    //    //$this->clientMessageService->sendTelegram($message->text, );
                    //    $model = new ClientMessage();
                    //    $model->client_id = $userState->user->id;
                    //    $model->text = $message->text;
                    //    $model->is_admin = false;
                    //    $model->status = ClientMessageStatusEnum::UNREAD;
                    //    $model->saveOrFail();
                    }
                    $this->answersMap->start($userState);
                    $userState->user->setStart();
                    return true;
                }
            }
        } catch (BadRequestException | ForbiddenException | InvalidTokenException | Throwable $e) {
            DebugTnHelper::exception($e);
            if (isset($message)) {
                DebugTnHelper::write($message);
            }
        }
        return true;
    }
}
