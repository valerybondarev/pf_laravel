<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $device_id
 * @property string $nickname
 *
 * @property Collection|Step[] $steps
 */
class Session extends Model
{
    protected $fillable = [
        'name',
        'device_id',
        'nickname',
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }

}
