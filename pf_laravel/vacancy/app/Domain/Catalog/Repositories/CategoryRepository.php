<?php

namespace App\Domain\Catalog\Repositories;

use App\Base\DataProviders\EloquentDataProvider;
use App\Base\Interfaces\DataProviderInterface;
use App\Base\Repositories\BaseEloquentRepository;
use App\Domain\Application\Enums\DefaultStatusEnum;
use App\Domain\Catalog\Entities\Category;
use App\Domain\Catalog\Enums\CategoryStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class ProductRepository
 *
 * @package App\Domain\News\Repositories
 *
 * @method Category|null findActive(array $params = [])
 */
class CategoryRepository extends BaseEloquentRepository implements DataProviderInterface
{
    protected function modelClass(): string
    {
        return Category::class;
    }

    protected function query(): Builder
    {
        return parent::query()->where('status', '!=', DefaultStatusEnum::DELETED);
    }

    protected function active(): Builder
    {
        return $this->query()->where('status', DefaultStatusEnum::ACTIVE);
    }
    public function firstActive(): Model|Builder|null
    {
        return $this->active()->first();
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
        if ($value = \Arr::get($parameters, 'category')) {
            $query->where('alias', '=', $value);
        }
        return $query;
    }

    public function toEloquentDataProvider(): EloquentDataProvider
    {
        return new EloquentDataProvider($this->query());
    }
}
