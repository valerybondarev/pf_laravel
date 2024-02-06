<?php


namespace App\Domain\PhoneCode\Services;


use App\Base\Exceptions\ExternalServiceException;
use Illuminate\Support\Facades\Log;
use LogicException;
use Zelenin\SmsRu\Api;
use Zelenin\SmsRu\Auth\ApiIdAuth;
use Zelenin\SmsRu\Entity\Sms;
use Zelenin\SmsRu\Exception\Exception;
use Zelenin\SmsRu\Response\SmsResponse;

/**
 * Class SmsService
 *
 * @package App\Services
 */
class SmsService
{
    private mixed $sender;
    private Api   $client;

    public function __construct()
    {
        $this->client = new Api(new ApiIdAuth(config('services.sms.app_id')));
        $this->sender = config('services.sms.sender');
    }

    public function send($phone, $text): bool
    {
        try {
            $sms = $this->createSms($phone, $text);
            return $this->resolveSmsResponse($sms, $this->client->smsSend($sms));
        } catch (Exception $e) {
            throw new ExternalServiceException('Error while sending SMS.', 0, $e);
        }
    }

    private function createSms($phone, $text): Sms
    {
        $sms = new Sms($phone, $text);

        if (!empty($this->sender)) {
            $sms->from = $this->sender;
        }

        $sms->test = config('services.sms.test_mode');

        return $sms;
    }

    private function resolveSmsResponse(Sms $sms, SmsResponse $response): bool
    {
        if ((int)$response->code === 100) {
            Log::info('Отправка смс: ' . $response->code . ': ' . $response->getDescription() . PHP_EOL . $sms->text);
            return true;
        }

        Log::error('Ошибка при отправке смс: ' . $response->code . ': ' . $response->getDescription());
        throw new LogicException(__('user.sms.errorSend'));
    }
}
