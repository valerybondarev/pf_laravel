<?php

namespace App\Domain\Catalog\Repositories;

use App\Base\Repositories\BaseEloquentRepository;
use App\Domain\Catalog\Entities\Brand;
use App\Domain\Catalog\Entities\Category;
use App\Domain\Catalog\Entities\Product;
use App\Domain\Catalog\Enums\CategoryStatus;
use App\Domain\News\Enums\NewsStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Class ProductRepository
 *
 * @package App\Domain\News\Repositories
 *
 * @method Product|null findActive(array $params = [])
 */
class BrandRepository extends BaseEloquentRepository
{
    protected function modelClass(): string
    {
        return Brand::class;
    }

    protected function query(): Builder
    {
        return parent::query()->where('status', '!=', NewsStatus::DELETED);
    }

    protected function active(): Builder
    {
        return $this->query()->where('status', CategoryStatus::ACTIVE);
    }

    public function getLast($limit = 4)
    {
        return $this->active()->latest('published_at')->orderBy('published_at', 'ASC')->orderBy('id', 'DESC')->limit($limit)->get();
    }

    public function getByTitle($title)
    {
        return parent::query()->where('title', '=', $title)->first();
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
        if ($value = \Arr::get($parameters, 'brand')) {
            $query->where('alias', '=', $value);
        }
        return $query;
    }
}
