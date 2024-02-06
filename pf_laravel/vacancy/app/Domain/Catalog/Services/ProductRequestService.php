<?php

namespace App\Domain\Catalog\Services;

use App\Base\Interfaces\ManageServiceInterface;
use App\Base\Services\BaseService;
use App\Domain\Catalog\Entities\ProductRequest;
use App\Domain\Catalog\Enums\ProductRequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ProductRequestService extends BaseService implements ManageServiceInterface
{
    public function create(array $data): ProductRequest
    {
        $model = new ProductRequest();
        $model->status = ProductRequestStatus::UNREAD;
        return $this->update($model, $data);
    }

    /**
     * @throws \Throwable
     */
    public function update(Model|ProductRequest $model, array $data): ProductRequest
    {
        $model->product_id = Arr::get($data, 'product_id', $model->product_id);
        $model->name = Arr::get($data, 'name', $model->name);
        $model->email = Arr::get($data, 'email', $model->email);
        $model->phone = Arr::get($data, 'phone', $model->phone);

        $model->status = Arr::get($data, 'status', $model->status);

        $model->saveOrFail();

        return $model;
    }

    public function destroy(Model $model): bool
    {
        return $model->forceFill(['status' => ProductRequestStatus::DELETED])->save();
    }
}
