<?php

namespace App\Domain\Application\File\Pivots;

use App\Domain\Application\File\Entities\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Fileable
 *
 * @package App\Domain\Application\Pivots
 *
 * @property string $file_id
 * @property string $type
 *
 * @property int    $fileable_type
 * @property int    $fileable_id
 *
 * @property Model  $fileable
 * @property File   $file
 */
class Fileable extends MorphPivot
{
    protected $fillable = ['type'];

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
