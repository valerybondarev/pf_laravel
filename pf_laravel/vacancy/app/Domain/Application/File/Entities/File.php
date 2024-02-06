<?php

namespace App\Domain\Application\File\Entities;

use App\Base\Models\UuidBaseModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property string           $id
 * @property string           $disk
 * @property string           $name
 * @property string           $path
 * @property string           $extension
 * @property int              $size
 *
 * @property Carbon           $created_at
 * @property Carbon           $updated_at
 * @property Carbon           $deleted_at
 *
 * @property-read bool        $isExists
 * @property-read string|null $url
 * @property-read string|null $fullPath
 * @property-read string|null $basename
 * @property-read string|null $dirname
 * @property-read string|null $filename
 */
class File extends UuidBaseModel
{
    public function getIsExistsAttribute(): bool
    {
        return Storage::disk($this->disk)->exists($this->path);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getFullPathAttribute(): ?string
    {
        return Storage::disk($this->disk)->path($this->path);
    }

    public function getBasenameAttribute(): ?string
    {
        return pathinfo($this->fullPath, PATHINFO_BASENAME);
    }

    public function getDirnameAttribute(): ?string
    {
        return pathinfo($this->fullPath, PATHINFO_DIRNAME);
    }

    public function getFilenameAttribute(): ?string
    {
        return pathinfo($this->fullPath, PATHINFO_FILENAME);
    }
}
