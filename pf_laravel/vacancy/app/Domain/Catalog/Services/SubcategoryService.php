<?php

namespace App\Domain\Catalog\Services;

use App\Base\Interfaces\ManageServiceInterface;
use App\Base\Services\BaseService;
use App\Domain\Catalog\Entities\Category;
use App\Domain\Catalog\Entities\Subcategory;
use App\Domain\Catalog\Enums\CategoryStatus;
use App\Domain\Catalog\Enums\SubcategoryStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class SubcategoryService extends BaseService implements ManageServiceInterface
{
    public function create(array $data): Subcategory
    {
        $model = new Subcategory();
        $model->status = SubcategoryStatus::ACTIVE;
        return $this->update($model, $data);
    }

    public function update(Model|Subcategory $model, array $data): Subcategory
    {
        $model->category_id = Arr::get($data, 'category_id', $model->category_id);
        $model->title = Arr::get($data, 'title', $model->title);
        $model->status = Arr::get($data, 'status', $model->status);
        $model->alias = Arr::get($data, 'alias', $model->alias) ?: $this->slug($model, $model->title, $model->id, 'alias');

        $model->saveOrFail();

        return $model;
    }

    public function destroy(Model $model): bool
    {
        return $model->forceFill(['status' => SubcategoryStatus::DELETED])->save();
    }
}
