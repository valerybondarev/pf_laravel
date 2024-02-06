<?php

namespace App\Domain\Application\File\Repositories;

use App\Base\Repositories\BaseEloquentRepository;
use App\Domain\Application\File\Entities\File;
use Illuminate\Database\Eloquent\Builder;

class FileRepository extends BaseEloquentRepository
{
    protected function modelClass(): string
    {
        return File::class;
    }

    private function applyTitle(File|Builder $query, $title): void
    {
        $query->where('title', $title);
    }

    private function applyPath(File|Builder $query, $path): void
    {
        $query->where('path', $path);
    }

    protected function applySize(Builder $query, $size): void
    {
        $query->where('size', $size);
    }

    protected function applyExtension(Builder $query, $extension): void
    {
        $query->where('extension', $extension);
    }
}
