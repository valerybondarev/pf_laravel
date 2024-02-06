<?php

namespace App\Domain\Catalog\Services;

use App\Base\Interfaces\ManageServiceInterface;
use App\Base\Services\BaseService;
use App\Domain\Catalog\Entities\AdminBrand;
use App\Domain\Catalog\Enums\AdminBrandStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class AdminBrandService extends BaseService implements ManageServiceInterface
{
    public function create(array $data): AdminBrand
    {
        $model = new AdminBrand();
        $model->status = AdminBrandStatus::ACTIVE;
        return $this->update($model, $data);
    }

    public function update(Model|AdminBrand $model, array $data): AdminBrand
    {
        $model->title = Arr::get($data, 'title');
        $model->image_id = Arr::get($data, 'imageId');
        $model->status = Arr::get($data, 'status', $model->status);

        $model->saveOrFail();

        return $model;
    }

    public function destroy(Model $model): bool
    {
        return $model->forceFill(['status' => AdminBrandStatus::DELETED])->save();
    }
}
