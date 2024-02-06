<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $step_id
 * @property string $event;
 * @property int $source_id
 * @property int $result_id
 * @property string $from_status
 * @property string $to_status
 *
 * @property File|null $source
 * @property File|null $result
 */
class StepHistory extends Model
{
    protected $fillable = [
        'step_id',
        'event',
        'source_id',
        'result_id',
        'from_status',
        'to_status',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function result(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
