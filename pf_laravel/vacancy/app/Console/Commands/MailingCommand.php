<?php

namespace App\Console\Commands;

use App\Bot\Entities\Keyboard;
use App\Bot\Traits\MakesKeyboard;
use App\Domain\Client\Entities\Client;
use App\Domain\Client\Repositories\ClientRepository;
use App\Domain\Mailing\Entities\Mailing;
use App\Domain\Mailing\Entities\MailingResult;
use App\Domain\Mailing\Enums\MailingButtonListEnum;
use App\Domain\Mailing\Enums\MailingStatusEnum;
use App\Domain\Mailing\Enums\MailingWorkingEnum;
use App\Domain\Mailing\Repositories\MailingRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use tsvetkov\telegram_bot\exceptions\BadRequestException;
use tsvetkov\telegram_bot\exceptions\ForbiddenException;
use tsvetkov\telegram_bot\exceptions\InvalidTokenException;
use tsvetkov\telegram_bot\TelegramBot;

class MailingCommand extends Command
{
    protected $signature = 'mailing:send';

    public function __construct(
        private ClientRepository $clientRepository,
        private MailingRepository $mailingRepository,
    )
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $mailings = $this->mailingRepository->getForSend();
        $bot = new TelegramBot(config('bot.token'));
        foreach ($mailings as $mailing) {
            $clients = $this->clientRepository->allVerified();//с этим нужно определиться. Или добавить статус в настройки рассылки
            $keyboard = $this->buildKeyboard($mailing);
            $this->info($mailing->id);
            $mailing->working = MailingWorkingEnum::WORKING;
            $mailing->saveOrFail();
            $results = [];
            if ($mailing->clientLists->count()) {
                $clientIds = [];
                foreach ($mailing->clientLists as $clientList) {
                    $this->sendMessage($bot, $mailing, $clientList->clients, $keyboard, $results, $clientIds);
                }
            } else {
                $this->sendMessage($bot, $mailing, $clients, $keyboard, $results);
            }

            $mailing->status = MailingStatusEnum::SEND;
            $mailing->saveOrFail();
            foreach ($results as $result) {
                $mailingResult = new MailingResult($result);
                $mailingResult->saveOrFail();
            }
        }
        return 0;
    }

    /**
     * @param TelegramBot    $bot
     * @param                $mailing
     * @param Client[]|Collection $clients
     * @param Keyboard       $keyboard
     * @param                $results
     * @param array          $clientIds
     *
     * @return void
     */
    private function sendMessage(TelegramBot $bot, $mailing, $clients, Keyboard $keyboard, &$results, array &$clientIds = []): void
    {
        foreach ($clients as $client) {
            if (!in_array($client->id, $clientIds)) {
                $clientIds[] = $client->id;
                $this->info($client->telegram_id);
                try {
                    $bot->sendMessage($client->telegram_id, $mailing->text, 'html', $keyboard->keyboard);
                    $results[] = [
                        'client_id'  => $client->id,
                        'mailing_id' => $mailing->id,
                        'success'    => 1,
                        'error'      => null,
                        'created_at' => Carbon::now(),
                    ];
                } catch (BadRequestException|ForbiddenException|InvalidTokenException $e) {
                    $results[] = [
                        'client_id'  => $client->id,
                        'mailing_id' => $mailing->id,
                        'success'    => 0,
                        'error'      => $e->getMessage(),
                        'created_at' => Carbon::now(),
                    ];
                }
            }
        }
    }

    use MakesKeyboard;

    /**
     * @param Mailing $mailing
     *
     * @return Keyboard
     */
    public function buildKeyboard(Mailing $mailing): Keyboard
    {
        $keyboard = new Keyboard();
        foreach ($mailing->buttons as $button) {
            $buttonClass = MailingButtonListEnum::relationByAction($button->action);
            $buttonClass->addToKeyboard($keyboard, $button);
        }
        return $keyboard;
    }
}
