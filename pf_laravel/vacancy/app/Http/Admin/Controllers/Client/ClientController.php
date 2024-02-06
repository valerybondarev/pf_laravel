<?php

namespace App\Http\Admin\Controllers\Client;

use App\Domain\Client\Enums\ClientRoleEnum;
use App\Domain\Client\Enums\ClientStatusEnum;
use App\Domain\Client\Repositories\ClientRepository;
use App\Domain\Client\Repositories\SportsCategoryRepository;
use App\Domain\Client\Services\ClientMessageService;
use App\Domain\Client\Services\ClientService;
use App\Domain\TourClub\Repositories\TourClubRepository;
use App\Http\Admin\Controllers\ResourceController;
use App\Domain\Client\Entities\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;
use tsvetkov\telegram_bot\exceptions\BadRequestException;
use tsvetkov\telegram_bot\exceptions\ForbiddenException;
use tsvetkov\telegram_bot\exceptions\InvalidTokenException;
use View;

/**
 * This is the controller class for table "clients".
 * Class App\Http\Admin\Controllers\Client
 *
 * @package  App\Http\Admin\Controllers\Client\ClientController
 */
class ClientController extends ResourceController
{
    public function __construct(
        ClientRepository $repository,
        ClientService $service,
        private ClientMessageService $clientMessageService,
        private TourClubRepository $tourClubRepository,
        private SportsCategoryRepository $sportsCategoryRepository
    )
    {
        parent::__construct($repository, $service);
    }

    protected function viewParameters(): array
    {
        return [
            'roles'           => ClientRoleEnum::labels(),
            'statuses'        => ClientStatusEnum::labels(),
            'tourClubs'       => $this->tourClubRepository->allActive(),
            'sportCategories' => $this->sportsCategoryRepository->allActive(),
        ];
    }

    protected function rules($model = null): array
    {
        return [
            'tourClubId'        => 'nullable|numeric|exists:tour_clubs,id',
            'sportsCategoryId'  => 'nullable|numeric|exists:sports_categories,id',
            'command'           => 'nullable|string|max:255',
            'telegramId'        => 'required|numeric',
            'username'          => 'nullable|string|max:255',
            'firstName'         => 'nullable|string|max:255',
            'lastName'          => 'nullable|string|max:255',
            'middleName'        => 'nullable|string|max:255',
            'phone'             => 'nullable|string|max:255',
            'yearInTk'          => 'nullable|string|max:255',
            'statusLearn'       => 'required|string|max:255',
            'yearInUniversity'  => 'nullable|numeric',
            'department'        => 'nullable|string|max:255',
            'group'             => 'nullable|string|max:255',
            'vkLink'            => 'nullable|string|max:255',
            'liveInDorm'        => 'nullable|numeric',
            'workOrganization'  => 'nullable|string|max:255',
            'campingExperience' => 'nullable|string|max:255',
            'status'            => 'required|string|max:255',
            'registeredAt'      => 'nullable|date',
            'points'            => 'nullable|numeric',
            'mailingNews'       => 'required|numeric',
            'mailingEvents'     => 'required|numeric',
            'start'             => 'required|numeric',
            'role'              => 'required|string|in:' . implode(',', ClientRoleEnum::keys()),
        ];
    }

    protected function resourceClass(): string
    {
        return Client::class;
    }

    public function clearTests(Client $client)
    {
        try {
            $client->answers()->detach($client->answers->map->id->all());
            return response()->json([
                'success' => true,
            ]);
        } catch (BadRequestException|InvalidTokenException|ForbiddenException|Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function send(Client $client, Request $request)
    {
        $data = $request->validate([
            'text' => 'required|string|max:4095',
        ]);
        try {
            $clientMessage = $this->clientMessageService->sendTelegram($data['text'], $client);
            return response()->json([
                'success'      => true,
                'data'         => $clientMessage,
                'messageBlock' => View::make('admin.clients.form.messages.messageBlock', ['clientMessage' => $clientMessage,])
                    ->render(),
            ]);
        } catch (BadRequestException|InvalidTokenException|ForbiddenException|Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function test(Client $client, Request $request)
    {
        $data = $request->validate([
            'text'     => 'nullable|string',
            'callback' => 'nullable|string',
        ]);
        if ($data['callback']) {
            $callback = $this->getPayload($data['callback']);
        } else {
            $callback = $this->getText($data['text']);
        }
        $r = Http::post(route('api.bot.webhook'), json_decode($callback, true));
        return response()->json([
            'success' => true,
            'message' => 'Отправлено',
            'asd1232' => $r->json(),
            'asd123'  => config('app.local'),
        ]);
    }

    public function errorStories(Client $client)
    {
        $text =
            'Bears have already checked everything and told us that the Insta story is not valid or there was no mention  :( Could you try again?';
        try {
            $clientMessage = $this->clientMessageService->sendTelegram($text, $client);
            $client->setCommand(['main.errorStories']);
            return response()->json([
                'success'      => true,
                'data'         => $clientMessage,
                'messageBlock' => View::make('admin.clients.form.messages.messageBlock', ['clientMessage' => $clientMessage,])
                    ->render(),
            ]);
        } catch (BadRequestException|InvalidTokenException|ForbiddenException|Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function successRegister(Client $client)
    {
        $client->setCommand(['main.successRegister']);
        $text =
            'Bears have already checked everything and told us that you are incredible. Now you are officially participating in the competition!';
        try {
            $clientMessage = $this->clientMessageService->sendTelegram($text, $client);
            $client->setCommand(['main.successRegister']);
            return response()->json([
                'success'      => true,
                'data'         => $clientMessage,
                'messageBlock' => View::make('admin.clients.form.messages.messageBlock', ['clientMessage' => $clientMessage,])
                    ->render(),
            ]);
        } catch (BadRequestException|InvalidTokenException|ForbiddenException|Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function getText($text)
    {
        return '{
    "update_id": 256555716,
    "message": {
        "message_id": 34396,
        "from": {
            "id": 433869407,
            "is_bot": false,
            "first_name": "Денис",
            "last_name": "Юнусбаев",
            "username": "DenzlBro",
            "language_code": "ru"
        },
        "chat": {
            "id": 433869407,
            "first_name": "Денис",
            "last_name": "Юнусбаев",
            "username": "DenzlBro",
            "type": "private"
        },
        "date": 1652887073,
        "text": "' . $text . '",
        "entities": [
            {
                "offset": 0,
                "length": 6,
                "type": "bot_command"
            }
        ]
    }
}';
    }

    private function getPayload($callback)
    {
        return '{
  "update_id": 256533308,
  "callback_query": {
    "id": "1863454915896610091",
    "from": {
      "id": 433869407,
      "is_bot": false,
      "first_name": "Денис",
      "last_name": "Юнусбаев",
      "username": "DenzlBro",
      "language_code": "ru"
    },
    "message": {
      "message_id": 18848,
      "from": {
        "id": 1858724268,
        "is_bot": true,
        "first_name": "Trade Fox System Bot",
        "username": "TradeFoxSystemBot"
      },
      "chat": {
        "id": 433869407,
        "first_name": "Денис",
        "last_name": "Юнусбаев",
        "username": "DenzlBro",
        "type": "private"
      },
      "date": 1638431910,
      "edit_date": 1638525435,
      "text": "Текст сообщения",
      "entities": [
      ],
      "reply_markup": {
        "inline_keyboard": [
          [
            {
              "text": "Отчет",
              "callback_data": "[\"trade.reportOne\",[111]]"
            },
            {
              "text": "Обновить",
              "callback_data": "[\"trade.botInfo\",[111]]"
            }
          ]
        ]
      }
    },
    "chat_instance": "-1481801723294279762",
    "data": "' . addslashes($callback) . '"
  }
}';
    }


    public function downloadClientsBase()
    {
        $clients = $this->repository->allActive();
        $test = $this->testRepository->findActive();
        $clientFiltered = $clients->map(function (Client $i) use ($test) {
            unset($i->command);
            //unset($i->answers);
            //unset();
            $i['cat_type_id'] = $i->getTestResult($test)?->id;
            $i['cat_type'] = $i->getTestResult($test)?->title;
            //$i['answers'] = 'null';
            //dd($i);
            return $i;
        })->map(function (Client $i) use ($test) {
            //unset($i->command);
            unset($i->answers);
            unset($i['answers']);
            //unset();
            $i['cat_type_id'] = $i->getTestResult($test)?->id;
            $i['cat_type'] = $i->getTestResult($test)?->title;
            //$i['answers'] = 'null';
            //dd($i);
            return $i;
        })->toArray();
        foreach ($clientFiltered as $index => $item) {
            unset($clientFiltered[$index]['answers']);
        }
        $this->arrayToCsvDownload($clientFiltered);
    }

    function arrayToCsvDownload($array, $filename = "cats-bot-clients.csv", $delimiter = ";")
    {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');

        // open the "output" stream
        // see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
        $f = fopen('php://output', 'w');
        if (count($array) > 0) {
            fputcsv($f, array_keys($array[0]), $delimiter);
        }

        foreach ($array as $line) {
            fputcsv($f, $line, $delimiter);
        }
    }
}
