<?php

namespace App\Domain\PhoneCode\Services;

use App\Domain\PhoneCode\Entities\PhoneCode;
use App\Domain\PhoneCode\Repositories\PhoneCodeRepository;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Class PhoneService
 *
 * @package App\Services\User
 */
class PhoneCodeService
{
    private const PHONE_CODE_EXPIRES = 120;

    public function __construct(
        private SmsService $smsService,
        private PhoneCodeRepository $repository,
    )
    {
    }

    /**
     * @param $phone
     *
     * @return PhoneCode|null
     * @throws Throwable
     */
    public function create($phone): ?PhoneCode
    {
        $this->beforeCreate($phone);
        $this->destroyPrevious($phone);

        $phoneNumber = phone($phone);

        $phoneCode = new PhoneCode();
        $phoneCode->phone = $phoneNumber;
        $phoneCode->phone_e164 = $phoneNumber->formatE164();
        $phoneCode->code = $this->generateCode();
        $phoneCode->expires_at = now()->addSeconds(self::PHONE_CODE_EXPIRES);

        return DB::transaction(function () use ($phoneCode) {
            $this->smsService->send($phoneCode->phone_e164, __('sms.message', ['code' => $phoneCode->code]));
            $phoneCode->save();
            return $phoneCode;
        });
    }

    public function submit($phone, $code): bool
    {
        if ($phoneCode = $this->repository->findActive(['phone' => $phone, 'code' => $code])) {
            $phoneCode->forceFill(['confirmed_at' => now()])->saveOrFail();
            return true;
        }

        return false;
    }

    private function beforeCreate($phone)
    {
        if ($this->repository->exists(['phone' => $phone])) {
            abort(403, __('sms.sent'));
        }
    }

    private function destroyPrevious($phone)
    {
        $this->repository->delete(['phone' => $phone]);
    }

    private function generateCode(): string
    {
        $length = 4;
        $str = '';
        while ($length--) {
            $str .= random_int(0, 9);
        }
        return $str;
    }
}
