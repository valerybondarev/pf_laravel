<?php

namespace App\Domain\Application\File\Pivots;

use App\Domain\Application\File\Entities\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Imageable
 *
 * @package App\Domain\Application\Pivots
 *
 * @property string $image_id
 * @property string $type
 *
 * @property int    $imageable_type
 * @property int    $imageable_id
 *
 * @property Model  $imageable
 * @property Image  $image
 */
class Imageable extends MorphPivot
{
    protected $fillable = ['type'];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
