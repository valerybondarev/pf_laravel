<?php

namespace App\Bot\Helpers;

use App\Domain\Client\Repositories\ClientRepository;
use tsvetkov\telegram_bot\exceptions\BadRequestException;
use tsvetkov\telegram_bot\exceptions\ForbiddenException;
use tsvetkov\telegram_bot\exceptions\InvalidTokenException;
use tsvetkov\telegram_bot\TelegramBot;

class DebugTnHelper
{
    public function __construct(
        private ClientRepository $clientRepository
    )
    {
    }

    public static function write($text, $userIds = 433869407, $disableNotification = null): string
    {
        $bot = new TelegramBot(config('bot.token'));

        if (is_array($text) || is_object($text)) {
            $text = '<pre>' . json_encode($text, 256 | JSON_PRETTY_PRINT) . '</pre>';
        }
        $debugBackTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2); //запрашиваем информацию по вызванным функциям до 2 уровня
        $textTrace = env('APP_DEBUG') && isset($debugBackTrace[1]) ? PHP_EOL . 'Function '
                                                                     . $debugBackTrace[1]['function']
                                                                     . ' Line '
                                                                     . ($debugBackTrace[1]['line'] ?? '')
                                                                     . PHP_EOL . 'Class '
                                                                     . $debugBackTrace[1]['class'] : '';
        $text .= $textTrace;
        $text = str_replace('<br>', "\n", $text);
        $text = strip_tags($text, '<code><b><a><pre></pre>');//разрешаем только некоторые теги
        $response = '';
        $text = substr($text, 0, 4000);
        try {
            $bot->sendMessage(
                $userIds,
                $text,
                'HTML',
                null,
                null,
                $disableNotification
            );
        } catch (BadRequestException|ForbiddenException|InvalidTokenException $e) {
            \Log::error($e->getMessage());
        }

        return $response;
    }

    /**
     * @param       $exception
     * @param int   $userId
     * @param array $params
     *
     * @return void
     */
    public static function exception($exception, int $userId = 433869407, array $params = [])
    {
        $bot = new TelegramBot(config('bot.token'));
        $text = 'Message:```' . PHP_EOL . $exception->getMessage() . '```' . PHP_EOL;
        $text .= 'File:```' . PHP_EOL . $exception->getFile() . ":" . $exception->getLine() . '```' . PHP_EOL;
        //if (isset($params['notTrace']) && !$params['notTrace']) {
        //    $text .= 'Trace:```' . PHP_EOL . substr($exception->getTraceAsString(), 0, $params['traceLimit'] ?? 1000) . '```'
        //             . PHP_EOL;
        //}
        $text = substr($text, 0, 4095);
        try {
            $bot->sendMessage(
                $userId,
                $text,
            );
        } catch (BadRequestException|ForbiddenException|InvalidTokenException $e) {
            self::write($e->getMessage());
        }
    }

    /**
     * Отправляет сообщение с ошибкой какой либо выбранной группе
     *
     * @param $exception
     * @param array $roleNames
     * @param array $params
     *
     * @return void
     */
    public function exceptionToGroup($exception, array $roleNames = ['manager'], array $params = [])
    {
        $params = array_replace(['notTrace' => true], $params);
        $roleNames = array_diff($roleNames,  ['user']);
        //Проверка есть ли фильтрующие данные. Если не будет, то отправится всем пользователям
        if (count($roleNames) > 0) {
            $users = $this->clientRepository->searchActive(['role' => $roleNames]);
            foreach ($users as $user) {
                self::exception($exception, $user->telegram_id, $params);
            }
        }
    }
}
