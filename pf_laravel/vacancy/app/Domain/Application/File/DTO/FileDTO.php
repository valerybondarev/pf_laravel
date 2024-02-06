<?php

namespace App\Domain\Application\File\DTO;

use App\Domain\Application\File\Entities\File;
use App\Domain\Application\File\Entities\Image;
use Illuminate\Http\UploadedFile;

class FileDTO
{
    public function __construct(
        public mixed $source,
        public string $name,
        public string $disk = 'public',
        public ?string $path = null,
        public string $directory = 'uploads/files',
    )
    {
    }

    public static function createFromFile(File $file): static
    {
        return new static(
            $file->fullPath,
            $file->name,
            $file->disk,
            $file->path,
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
