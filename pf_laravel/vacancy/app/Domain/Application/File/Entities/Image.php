<?php

namespace App\Domain\Application\File\Entities;

use App\Base\Models\UuidBaseModel;
use App\Domain\Application\File\DTO\ImageDTO;
use App\Domain\Application\File\Services\ImageService;
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
class Image extends UuidBaseModel
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

    public function fit(int $width, int $height): static
    {
        return app(ImageService::class)->fit(ImageDTO::createFromImage($this), $width, $height);
    }

    public function crop(int $width, int $height, int $x, int $y): static
    {
        return app(ImageService::class)->crop(ImageDTO::createFromImage($this), $width, $height, $x, $y);
    }

    public function resize(int $width, int $height): static
    {
        return app(ImageService::class)->resize(ImageDTO::createFromImage($this), $width, $height);
    }

    public function widen(int $width): static
    {
        return app(ImageService::class)->widen(ImageDTO::createFromImage($this), $width);
    }

    public function encode(string $extension): static
    {
        return app(ImageService::class)->encode(ImageDTO::createFromImage($this), $extension);
    }
}
