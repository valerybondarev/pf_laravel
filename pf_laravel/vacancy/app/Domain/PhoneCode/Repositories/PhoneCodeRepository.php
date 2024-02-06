<?php

namespace App\Domain\PhoneCode\Repositories;

use App\Base\Repositories\BaseEloquentRepository;
use App\Domain\PhoneCode\Entities\PhoneCode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class PhoneCodeRepository extends BaseEloquentRepository
{
    protected function modelClass(): string
    {
        return PhoneCode::class;
    }

    /**
     * @return Builder
     *
     * @see PhoneCode::scopeActive
     */
    protected function active(): Builder
    {
        return $this->query()->active();
    }

    protected function applyParameters(Builder $query, array $parameters = []): Builder
    {
        if ($phone = Arr::get($parameters, 'phone')) {
            $query->where('phone_e164', phone($phone, 'ru')->formatE164());
        }
        if ($code = Arr::get($parameters, 'code')) {
            $query->where('code', $code);
        }
        if ($status = Arr::get($parameters, 'status')) {
            $query->where('status', $status);
        }

        return parent::applyParameters($query, $parameters);
    }
}
