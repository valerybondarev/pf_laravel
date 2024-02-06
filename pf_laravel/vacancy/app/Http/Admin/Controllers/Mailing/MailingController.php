<?php

namespace App\Http\Admin\Controllers\Mailing;

use App\Bot\Entities\Keyboard;
use App\Bot\Enums\ButtonTypeEnum;
use App\Bot\Helpers\DebugTnHelper;
use App\Bot\Helpers\KeyboardBuilder;
use App\Domain\Client\Repositories\ClientListRepository;
use App\Domain\Mailing\Entities\MailingButton;
use App\Domain\Mailing\Enums\MailingButtonListEnum;
use App\Domain\Mailing\Enums\MailingStatusEnum;
use App\Http\Admin\Controllers\ResourceController;
use App\Domain\Mailing\Entities\Mailing;
use App\Domain\Mailing\Repositories\MailingRepository;
use App\Domain\Mailing\Services\MailingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use tsvetkov\telegram_bot\exceptions\BadRequestException;
use tsvetkov\telegram_bot\exceptions\ForbiddenException;
use tsvetkov\telegram_bot\exceptions\InvalidTokenException;
use tsvetkov\telegram_bot\TelegramBot;

/**
 * This is the controller class for table "mailings".
 * Class App\Http\Admin\Controllers\Mailing
 *
 * @package  App\Http\Admin\Controllers\Mailing\MailingController
 */
class MailingController extends ResourceController
{
    public function __construct(
        MailingRepository $repository,
        MailingService $service,
        private ClientListRepository $clientListRepository,
        private KeyboardBuilder $keyboardBuilder,
    )
    {
        parent::__construct($repository, $service);
    }

    /**
     * @throws ValidationException
     */
    public function sendTest(Request $request): JsonResponse
    {
        $data = $request->validate([
            'text'         => 'required|string|max:4095',
            'testUsername' => 'required',

            'buttons'         => 'nullable',
            'buttons.*.title' => 'required|string',
        ]);
        $keyboard = new Keyboard();
        if (isset($data['buttons']) && is_array($data['buttons'])) {
            foreach ($request->get('buttons') as $mailingButton) {
                $buttonClass = MailingButtonListEnum::relationByAction($mailingButton['action'])->validate($mailingButton['json']);
                $mailingButton['json'] = json_encode($mailingButton['json']);
                $buttonClass->addToKeyboard($keyboard, new MailingButton($mailingButton));
            }
            DebugTnHelper::write([$keyboard->keyboard]);
        }
        $bot = new TelegramBot(config('bot.token'));
        try {
            $r = $bot->sendMessage(
                $request->get('testUsername'),
                $request->get('text'),
                'html',
                $keyboard->keyboard
            );
        } catch (BadRequestException|ForbiddenException|InvalidTokenException $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ]);
        }
        return response()->json([
            'success' => true,
            'data'    => $r,
        ]);
    }

    protected function rules($model = null): array
    {
        return [
            'title'         => 'required|string|max:255',
            'text'          => 'required|string|max:4095',
            'status'        => 'required|string',
            'sendAt'        => 'required|date_format:d.m.Y G:i',
            'clientLists'   => 'nullable|array',
            'clientLists.*' => 'required|exists:client_lists,id',

            'buttons'          => 'nullable',
            'buttons.*.title'  => 'required|string',
            'buttons.*.action' => 'required|string',
            'buttons.*.json'   => 'nullable|array',
        ];
    }

    protected function resourceClass(): string
    {
        return Mailing::class;
    }

    protected function viewParameters(): array
    {
        return [
            'statuses'    => MailingStatusEnum::labels(),
            'buttonTypes' => MailingButtonListEnum::labels(),
            'clientLists' => $this->clientListRepository->allActive()->keyBy('id')->map->title,
        ];
    }

    public function getButton(Request $request): JsonResponse
    {
        $data = $request->validate([
            'action' => ['required', Rule::in(MailingButtonListEnum::keys())],
        ]);
        return response()->json([
            'success' => true,
            'data'    => view('admin.mailings.blocks.' . $data['action'], array_replace([
                'index'         => $request->get('index'),
                'mailingButton' => new MailingButton([
                    'action' => $data['action'],
                    'json'   => json_encode(MailingButtonListEnum::relationByAction($data['action'])->json),
                    'sort'   => 100,
                ]),
            ], $this->viewParameters()))->render(),
        ]);
    }
}
