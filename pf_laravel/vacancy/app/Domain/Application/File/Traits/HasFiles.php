<?php

namespace App\Domain\Application\File\Traits;

use App\Domain\Application\File\Entities\File;
use App\Domain\Application\File\Entities\Image;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Trait HasFiles
 *
 * @package App\Domain\Application\Traits
 *
 */
trait HasFiles
{
    protected function files($type = null): MorphToMany
    {
        $relation = $this->morphToMany(File::class, 'fileable');
        return $type ? $relation->wherePivot('type', $type) : $relation;
    }

    protected function images($type = null): MorphToMany
    {
        $relation = $this->morphToMany(Image::class, 'imageable');
        return $type ? $relation->wherePivot('type', $type) : $relation;
    }
}
