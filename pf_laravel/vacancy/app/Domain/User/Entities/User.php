<?php

namespace App\Domain\User\Entities;


use App\Domain\Application\File\Entities\Image;
use App\Domain\Application\File\Traits\HasFiles;
use App\Domain\Application\Language\Entities\Language;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as AbstractUser;
use Illuminate\Notifications\Notifiable;
use Propaganistas\LaravelPhone\Casts\RawPhoneNumberCast;
use Propaganistas\LaravelPhone\PhoneNumber;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 *
 * @package App\Domain\User\Entities
 * @mixin Eloquent
 *
 * @property int                                  $id
 * @property string                               $email
 * @property string                               $username
 * @property PhoneNumber|null                     $phone
 * @property string|null                          $phone_e164
 * @property string                               $password
 *
 * @property int                                  $avatar_id
 * @property int                                  $language_id
 *
 * @property string                               $last_name
 * @property string                               $first_name
 * @property string                               $middle_name
 * @property string                               $status
 *
 * @property string                               $remember_token
 *
 * @property Carbon|null                          $born_at
 * @property Carbon|null                          $phone_verified_at
 * @property Carbon|null                          $email_verified_at
 * @property Carbon|null                          $password_changed_at
 * @property Carbon|null                          $banned_at
 *
 * @property Carbon                               $created_at
 * @property Carbon                               $updated_at
 * @property Carbon|null                          $deleted_at
 *
 * @property-read Collection|UserSocialProvider[] $socialProviders
 * @property-read Image|null                      $avatar
 * @property-read Language|null                   $language
 */
class User extends AbstractUser implements MustVerifyEmail, Authenticatable
{
    use Notifiable, HasRoles, HasFiles, SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'phone' => RawPhoneNumberCast::class . ':RU',
    ];

    protected $dates = [
        'born_at',
        'phone_verified_at',
        'email_verified_at',
        'password_changed_at',
    ];

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function socialProviders(): HasMany
    {
        return $this->hasMany(UserSocialProvider::class);
    }

    public function getFullName($email = true): ?string
    {
        return trim($this->first_name . ' ' . $this->last_name) ?: $this->email ?: $this->username ?: $this->phone;
    }
}
