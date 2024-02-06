<?php

namespace App\Http\Api\Controllers\Bot;

use App;
use App\Bot\Command\MainCommand;
use App\Bot\Helpers\DebugTnHelper;
use App\Bot\Logic\AnswersMap;
use App\Bot\Store\UserState;
use App\Domain\Client\Entities\Client;
use App\Http\Api\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
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
class TestController extends Controller
{
    public function __construct(
        private AnswersMap $answersMap,
        private App\Domain\Client\Repositories\ClientRepository $clientRepository,
    )
    {
    }

    public function index(): JsonResponse
    {
        /** @var Client $client */
        $client = Client::query()->where('telegram_id', 433869407)->first();

        $update = json_decode(json_encode([
            'message' => [
                'from' => [
                    'id' => 433869407,
                    'username' => 'den',
                ]
            ]
        ]));
        /** @var UserState $userState */
        $userState = App::makeWith(UserState::class, ['update' => $update]);
        $membershipIsWork = $userState->user->tourClub->membership_enable && $userState->user->tourClub->membership_to > Carbon::now();
        if ($membershipIsWork) {
            $userState->user->createMemberShip();
        }
        $userState->user->events()->attach($userState->user->tourClub->futureEvents[0]->id, ['created_at' => Carbon::now()]);
        dd($userState->user->tourClub->futureEvents, $userState->user->refresh()->events);
        return response()->json([
            'data' => $client,
        ]);
    }
}
