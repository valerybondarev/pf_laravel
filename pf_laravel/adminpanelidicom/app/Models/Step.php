<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $session_id
 * @property int $step
 * @property array $options
 * @property int|null $file_id
 * @property string|null $exception
 * @property string $status
 *
 * @property File|null $file
 * @property StepHistory[]|Collection $histories
 */
class Step extends Model
{
    protected $fillable = [
        'session_id',
        'step',
        'options',
        'file_id',
        'exception',
        'status',
    ];

    protected $casts = [
        'options' => 'json',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(StepHistory::class);
    }
}
