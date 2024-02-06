<?php

namespace App\Domain\PhoneCode\Entities;

use App\Base\Models\BaseModel;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Propaganistas\LaravelPhone\Casts\RawPhoneNumberCast;
use Propaganistas\LaravelPhone\PhoneNumber;

/**
 * @mixin Eloquent
 *
 * @property integer     $id
 * @property PhoneNumber $phone
 * @property string      $phone_e164
 * @property string      $code
 * @property Carbon|null $confirmed_at
 * @property Carbon      $expires_at
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 * @property Carbon      $deleted_at
 */
class PhoneCode extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'phone' => RawPhoneNumberCast::class . ':RU',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('expires_at', '<=', now())
            ->whereNull('confirmed_at');
    }
}
