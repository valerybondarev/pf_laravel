<?php

namespace App\Base\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface ManageServiceInterface
{
    public function create(array $data): Model;

    public function update(Model $model, array $data): Model;

    public function destroy(Model $model): bool;

}
