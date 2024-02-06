<?php

namespace App\Domain\Application\File\DTO;

use App\Domain\Application\File\Entities\Image;
use Illuminate\Http\UploadedFile;

class ImageDTO
{
    public function __construct(
        public mixed $source,
        public string $name,
        public string $disk = 'public',
        public ?string $path = null,
        public string $directory = 'uploads/images',
    )
    {
    }

    public static function createFromImage(Image $image): static
    {
        return new static(
            $image->fullPath,
            $image->name,
            $image->disk,
            $image->path,
        );
    }

    public static function createFromUploadedFile(UploadedFile $uploadedFile): static
    {
        return new static(
            $uploadedFile,
            $uploadedFile->getClientOriginalName(),
        );
    }

    public static function createFromUrl(string $url): static
    {
        return new static(
            $url,
            basename($url)
        );
    }

}