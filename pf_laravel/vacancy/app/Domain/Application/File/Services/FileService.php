<?php

namespace App\Domain\Application\File\Services;

use App\Domain\Application\File\DTO\FileDTO;
use App\Domain\Application\File\Entities\File;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    public function __construct(private FileCrudService $crudService)
    {
    }

    public function create(FileDTO $fileDTO): File
    {
        return $this->save($fileDTO);
    }

    private function storage(string $disk = 'public'): FilesystemAdapter
    {
        return Storage::disk($disk);
    }

    private function save(FileDTO $fileDTO): File
    {
        $extension = \File::extension($fileDTO->name);
        $fileDTO->path = $fileDTO->path ?: $this->generatePath($fileDTO->directory, $extension, $fileDTO->disk);
        $this->storage($fileDTO->disk)->put($fileDTO->path, $fileDTO->source->get());

        return $this->crudService->create([
            'disk'      => $fileDTO->disk,
            'name'      => $fileDTO->name,
            'path'      => $fileDTO->path,
            'extension' => $extension,
            'size'      => $fileDTO->source->getSize(),
        ]);
    }

    private function generatePath(string $directory, string $extension, string $disk = 'public'): string
    {
        do {
            $path = $directory . DIRECTORY_SEPARATOR . Str::random(20) . '.' . $extension;
        } while ($this->storage($disk)->exists($path));
        return $path;
    }

    public function getRealPath(FileDTO $fileDTO, string $disk = 'public'): string
    {
        return $this->storage($disk)->path(/*$fileDTO->directory . DIRECTORY_SEPARATOR . */$fileDTO->path);
    }
}
