<?php

namespace App\Domain\Client\Repositories;


use App\Base\Repositories\BaseEloquentRepository;
use App\Domain\Client\Entities\SportsCategory;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Base\Interfaces\DataProviderInterface;
use App\Domain\Client\Enums\SportsCategoryStatusEnum;

use App\Base\Traits\DataProviderTrait;

/**
 * This is the repository class for table "sports_categories".
 * Class SportsCategoryRepository
 *
 * @package  App\Domain\Client\Repositories
 * @method SportsCategory []|\Illuminate\Database\Eloquent\Collection search(array $parameters = [], int $limit = null)
 * @method SportsCategory []|\Illuminate\Database\Eloquent\Collection searchActive(array $parameters = [], int $limit = null)
 * @method SportsCategory|null oneActive(array $params = [])
 * @method SportsCategory|null find(array $params = [])
 * @method SportsCategory|null findActive(array $params = [])
 * @method SportsCategory[]|Collection allActive()
 */
class SportsCategoryRepository extends BaseEloquentRepository implements DataProviderInterface
{
    use DataProviderTrait;

    protected function modelClass(): string
    {
        return SportsCategory::class;
    }

    public static function make(): static
    {
        return new static();
    }

    public function getLast($limit = 15)
    {
        return $this->active()->latest('columnName')->orderBy('columnName', 'ASC')->orderBy('id', 'DESC')->limit($limit)->get();
    }

    protected function active(): Builder
    {
        return $this->query()->where('status', '=', SportsCategoryStatusEnum::ACTIVE);
    }

    protected function applyParameters(Builder $query, array $parameters = []): Builder
    {
        return parent::applyParameters($query, $parameters)
            ->when(Arr::get($parameters, 'paramName'), fn(Builder $query, $value) => $query->where('paramName', $value));
    }

    /**
     * @return  Collection
     */
    public function getArrayForSelect(): Collection
    {
        return $this->active()->get()->mapWithKeys(function ($item) {
            return [$item['id'] => trim($item['title'])];
        });
    }
}
