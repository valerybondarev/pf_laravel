<?php

namespace App\Domain\Mailing\Repositories;


use App\Base\Repositories\BaseEloquentRepository;
use App\Domain\Mailing\Entities\Mailing;
use App\Domain\Mailing\Enums\MailingWorkingEnum;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Base\Interfaces\DataProviderInterface;
use App\Domain\Mailing\Enums\MailingStatusEnum;

use App\Base\Traits\DataProviderTrait;

/**
 * This is the repository class for table "mailings".
 * Class MailingRepository
 *
 * @package  App\Domain\Mailing\Repositories
 * @method Mailing []|Collection search(array $parameters = [], int $limit = null)
 * @method Mailing []|Collection searchActive(array $parameters = [], int $limit = null)
 * @method Mailing|null one(string|int|null $id, bool $active = false)
 * @method Mailing|null oneActive(array $params = [])
 * @method Mailing|null find(array $params = [])
 * @method Mailing|null findActive(array $params = [])
 */
class MailingRepository extends BaseEloquentRepository implements DataProviderInterface
{
    use DataProviderTrait;

    public function modelClass(): string
    {
        return Mailing::class;
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
        return $this->newQuery()->where('status', '!=', MailingStatusEnum::DELETED);
    }

    protected function active(): Builder
    {
        return $this->newQuery()->where('status', '=', MailingStatusEnum::ACTIVE);
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

    /**
     * @return Mailing[]|Collection|array
     */
    public function getForSend(): Collection|array
    {
        return $this->active()
            ->where('working', MailingWorkingEnum::NOT_WORKING)
            ->where('send_at', '<=', Carbon::now('Europe/Moscow'))
            ->get();
    }
}
