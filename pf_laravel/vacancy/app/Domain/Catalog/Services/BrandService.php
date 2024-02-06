<?php

namespace App\Domain\Catalog\Services;

use App\Base\Interfaces\ManageServiceInterface;
use App\Base\Services\BaseService;
use App\Domain\Catalog\Entities\Brand;
use App\Domain\Catalog\Enums\BrandStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class BrandService extends BaseService implements ManageServiceInterface
{
    public function create(array $data): Brand
    {
        $model = new Brand();
        $model->status = BrandStatus::ACTIVE;
        return $this->update($model, $data);
    }

    public function update(Model|Brand $model, array $data): Brand
    {
        $model->title = Arr::get($data, 'title');
        $model->status = Arr::get($data, 'status', $model->status);
        $model->alias = Arr::get($data, 'alias', $model->alias) ?: $this->slug($model, $model->title, $model->id);

        $model->saveOrFail();

        return $model;
    }

    public function destroy(Model $model): bool
    {
        return $model->forceFill(['status' => BrandStatus::DELETED])->save();
    }
}
