<?php


namespace App\Domain\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;


/**
 * Class UserSocialProvider
 *
 * @package App\Domain\User\Entities
 *
 * @property integer   $id
 * @property integer   $user_id
 * @property string    $provider
 * @property string    $provider_id
 * @property string    $token
 * @property string    $attributes
 *
 * @property Carbon    $created_at
 * @property Carbon    $updated_at
 *
 * @property-read User $user
 */
class UserSocialProvider extends Model
{
    protected $guarded = ['id', 'user_id'];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
