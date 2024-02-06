<?php

namespace App\Domain\Catalog\Repositories;

use App\Base\DataProviders\EloquentDataProvider;
use App\Base\Interfaces\DataProviderInterface;
use App\Base\Repositories\BaseEloquentRepository;
use App\Domain\Catalog\Entities\Product;
use App\Domain\Catalog\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Class ProductRepository
 *
 * @package App\Domain\News\Repositories
 *
 * @method Product|null findActive(array $params = [])
 * @method Product|null oneActive(int $id)
 */
class ProductRepository extends BaseEloquentRepository implements DataProviderInterface
{
    protected function modelClass(): string
    {
        return Product::class;
    }

    protected function query(): Builder
    {
        return parent::query()->where('status', '!=', ProductStatus::DELETED);
    }

    protected function active(): Builder
    {
        return $this->query()->where('status', '=', ProductStatus::ACTIVE);
    }

    public function getLast($limit = 4)
    {
        return $this->active()->latest('published_at')->orderBy('published_at', 'ASC')->orderBy('id', 'DESC')->limit($limit)->get();
    }

    public function getByIdInCatalog($idInCatalog): \Illuminate\Database\Eloquent\Model|Product|Builder|null
    {
        return parent::query()->where('id_in_catalog', '=', $idInCatalog)->first();
    }

    /**
     * @return Collection
     */
    public function getProductArrayForSelect(): Collection
    {
        return $this->query()->select('id', 'title')->get()->mapWithKeys(function ($item) {
            return [$item['id'] => $item['title']];
        });
    }

    protected function applyParameters(Builder $query, array $parameters = []): Builder
    {
        if ($value = \Arr::get($parameters, 'subcategory_id')) {
            $query->where('subcategory_id', '=', $value);
        }
        if ($value = \Arr::get($parameters, 'brand_id')) {
            $query->where('brand_id', '=', $value);
        }
        if ($value = \Arr::get($parameters, 'alias')) {
            $query->where('alias', '=', $value)->whereNotNull('alias');
        }
        if ($value = \Arr::get($parameters, 'subcategory_ids')) {
            $query->whereIn('subcategory_id', $value);
        }
        if ($sortBy = \Arr::get($parameters, 'sortBy')) {
            switch ($sortBy) {
                case 'subcategory_id':
                    $query->orderBy('subcategory_id');
            }
        }
        if ($value = \Arr::get($parameters, 'search')) {
            $query->where(function ($query) use ($value) {
                $query->where('title', '=', $value)
                    ->orWhere('description', '=', $value)
                    ->orWhere('vendor_code', '=', $value);
            });
        }
        return $query;
    }

    public function toEloquentDataProvider(): EloquentDataProvider
    {
        return new EloquentDataProvider($this->query());
    }
}
