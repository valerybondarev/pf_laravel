<?php

namespace App\Domain\Catalog\Services;

use App\Base\Interfaces\ManageServiceInterface;
use App\Base\Services\BaseService;
use App\Domain\Catalog\Entities\Category;
use App\Domain\Catalog\Entities\CategoryKeyWord;
use App\Domain\Catalog\Enums\CategoryStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class CategoryService extends BaseService implements ManageServiceInterface
{
    public function create(array $data): Category
    {
        $model = new Category();
        $model->status = CategoryStatus::ACTIVE;
        return $this->update($model, $data);
    }

    public function update(Model|Category $model, array $data): Category
    {
        $model->title = Arr::get($data, 'title');
        $model->status = Arr::get($data, 'status', $model->status);
        if ($model->saveOrFail()) {
            $model->keyWords()->delete();
            foreach (Arr::get($data, 'keyWords', []) as $keyWordValue) {
                $keyWord = new CategoryKeyWord();
                $keyWord->category_id = $model->id;
                $keyWord->title = $keyWordValue;
                $keyWord->saveOrFail();
            }
        }

        return $model;
    }

    public function destroy(Model $model): bool
    {
        return $model->deleteOrFail();
    }
}
