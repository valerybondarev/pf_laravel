<?php

namespace App\Domain\Vacancy\Repositories;

use App\Base\Repositories\BaseEloquentRepository;
use App\Domain\Vacancy\Entities\Vacancy;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Base\Interfaces\DataProviderInterface;

use App\Base\Traits\DataProviderTrait;
/**
* This is the repository class for table "vacancies".
* Class VacancyRepository
*
* @package  App\Domain\Vacancy\Repositories
 * @method Vacancy []|Collection search(array $parameters = [], int $limit = null)
 * @method Vacancy []|Collection searchActive(array $parameters = [], int $limit = null)
 * @method Vacancy|null oneActive(array $params = [])
 * @method Vacancy|null find(array $params = [])
 * @method Vacancy|null findActive(array $params = [])
*/
class VacancyRepository extends BaseEloquentRepository implements DataProviderInterface
{
    use DataProviderTrait;

    protected function modelClass(): string
    {
        return Vacancy::class;
    }

    protected function applyParameters(Builder $query, array $parameters = []): Builder
    {
        return parent::applyParameters($query, $parameters)
            ->when(Arr::get($parameters, 'paramName'), fn(Builder $query, $value) => $query->where('paramName', $value))
            ;
    }
}
