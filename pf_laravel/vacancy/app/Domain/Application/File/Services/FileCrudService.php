<?php

namespace App\Domain\Application\File\Services;


use App\Base\Exceptions\MethodUnsupportedException;
use App\Base\Interfaces\ManageServiceInterface;
use App\Domain\Application\File\Entities\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use League\Flysystem\Util;

class FileCrudService implements ManageServiceInterface
{
    public function create($data): File
    {
        return File::forceCreate([
            'disk'      => Arr::get($data, 'disk', 'public'),
            'name'      => Arr::get($data, 'name'),
            'path'      => Util::normalizePath(Arr::get($data, 'path')),
            'extension' => Arr::get($data, 'extension'),
            'size'      => Arr::get($data, 'size'),
        ]);
    }

    public function update(Model $model, array $data): Model
    {
        throw new MethodUnsupportedException();
    }

    public function destroy(Model $model): bool
    {
        throw new MethodUnsupportedException();
    }
}