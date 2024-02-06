<?php

namespace App\Domain\Catalog\Repositories;

use App\Base\DataProviders\EloquentDataProvider;
use App\Base\Interfaces\DataProviderInterface;
use App\Base\Repositories\BaseEloquentRepository;
use App\Domain\Catalog\Entities\Product;
use App\Domain\Catalog\Entities\Subcategory;
use App\Domain\Catalog\Enums\CategoryStatus;
use App\Domain\Catalog\Enums\SubcategoryStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Class ProductRepository
 *
 * @package App\Domain\News\Repositories
 *
 * @method Product|null findActive(array $params = [])
 */
class SubcategoryRepository extends BaseEloquentRepository implements DataProviderInterface
{
    protected function modelClass(): string
    {
        return Subcategory::class;
    }

    protected function query(): Builder
    {
        return parent::query()->where('status', '!=', SubcategoryStatus::DELETED);
    }

    protected function active(): Builder
    {
        return $this->query()->where('status', CategoryStatus::ACTIVE);
    }

    public function getLast($limit = 4)
    {
        return $this->active()->latest('published_at')->orderBy('published_at', 'ASC')->orderBy('id', 'DESC')->limit($limit)->get();
    }

    public function getByTitle($title, $categoryId)
    {
        return parent::query()->where('title', '=', $title)->where('category_id', '=', $categoryId)->first();
    }

    /**
     * @return Collection
     */
    public function getArrayForSelect(): Collection
    {
        return $this->query()->select('id', 'title')->get()->mapWithKeys(function ($item) {
            return [$item['id'] => $item['title']];
        });
    }

    protected function applyParameters(Builder $query, array $parameters = []): Builder
    {
        if ($value = \Arr::get($parameters, 'subcategory')) {
            $query->where('alias', '=', $value);
        }
        return $query;
    }

    public function toEloquentDataProvider(): EloquentDataProvider
    {
        return new EloquentDataProvider($this->query());
    }
}
