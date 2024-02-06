<?php

namespace App\Domain\Client\Repositories;


use App\Base\Repositories\BaseEloquentRepository;
use App\Domain\Client\Entities\ClientList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Base\Interfaces\DataProviderInterface;
use App\Domain\Client\Enums\ClientListStatusEnum;

use App\Base\Traits\DataProviderTrait;

/**
 * This is the repository class for table "client_lists".
 * Class ClientListRepository
 *
 * @package  App\Domain\Client\Repositories
 * @method ClientList []|Collection search(array $parameters = [], int $limit = null)
 * @method ClientList []|Collection searchActive(array $parameters = [], int $limit = null)
 * @method ClientList|null one(string|int|null $id, bool $active = false)
 * @method ClientList|null oneActive(array $params = [])
 * @method ClientList|null find(array $params = [])
 * @method ClientList|null findActive(array $params = [])
 */
class ClientListRepository extends BaseEloquentRepository implements DataProviderInterface
{
    use DataProviderTrait;

    protected function modelClass(): string
    {
        return ClientList::class;
    }

    public static function make(): static
    {
        return new static();
    }

    public function getLast($limit = 15)
    {
        return $this->active()->latest('columnName')->orderBy('columnName', 'ASC')->orderBy('id', 'DESC')->limit($limit)->get();
    }
    protected function query(): Builder
    {
        return $this->newQuery()->where('status', '!=', ClientListStatusEnum::DELETED);
    }

    protected function active(): Builder
    {
        return $this->query()->where('status', '=', ClientListStatusEnum::ACTIVE);
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
