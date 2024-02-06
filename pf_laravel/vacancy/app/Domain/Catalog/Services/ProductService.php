<?php

namespace App\Domain\Catalog\Services;

use App\Base\Interfaces\ManageServiceInterface;
use App\Base\Services\BaseService;
use App\Domain\Catalog\Entities\Product;
use App\Domain\Catalog\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ProductService extends BaseService implements ManageServiceInterface
{
    public function create(array $data): Product
    {
        $model = new Product();
        $model->status = ProductStatus::ACTIVE;
        $model->id_in_catalog = Arr::get($data, 'id_in_catalog', $model->id_in_catalog);
        return $this->update($model, $data);
    }

    /**
     * @throws \Throwable
     */
    public function update(Model|Product $model, array $data): Product
    {
        $model->subcategory_id = Arr::get($data, 'subcategory_id', $model->subcategory_id);
        $model->title = Arr::get($data, 'title', $model->title);
        $model->alias = Arr::get($data, 'alias', $model->alias) ?: $this->slug($model, $model->title, $model->id, 'alias');
        //$model->description = Arr::get($data, 'description', $model->description);
        $model->price = Arr::get($data, 'price', 0);
        $model->vendor_code = Arr::get($data, 'vendor_code', $model->vendor_code);
        $model->id_in_catalog = Arr::get($data, 'id_in_catalog', $model->id_in_catalog);
        $model->stock = Arr::get($data, 'stock', $model->stock);
        $model->imported_at = Arr::get($data, 'imported_at', $model->imported_at);
        $model->image_id = Arr::get($data, 'image_id', $model->image_id);

        $model->status = Arr::get($data, 'status', $model->status);

        $model->saveOrFail();

        return $model;
    }

    public function destroy(Model $model): bool
    {
        return $model->forceFill(['status' => ProductStatus::DELETED])->save();
    }
}
