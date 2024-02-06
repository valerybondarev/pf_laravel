<?php

namespace App\Domain\Application\Language\Services;

use App\Base\Interfaces\ManageServiceInterface;
use App\Domain\Application\Language\Entities\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class LanguageManageService implements ManageServiceInterface
{
    public function create(array $data): Language
    {
        $model = new Language();

        return $this->update($model, $data);
    }

    public function update(Model|Language $model, array $data): Language
    {
        $model->code = Arr::get($data, 'code', $model->code);
        $model->name = Arr::get($data, 'title', $model->name);
        $model->is_default = Arr::get($data, 'is_default', $model->is_default);

        $model->saveOrFail();

        return $model;
    }

    public function destroy(Model|Language $model): bool
    {
        return $model->delete();
    }
}
