<?php

namespace App\Domain\Client\Repositories;


use App\Base\DataProviders\EloquentDataProvider;
use App\Base\Repositories\BaseEloquentRepository;
use App\Domain\Client\Entities\Client;
use App\Domain\Client\Enums\ClientRoleEnum;
use App\Domain\Event\Entities\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Base\Interfaces\DataProviderInterface;
use App\Domain\Client\Enums\ClientStatusEnum;

use App\Base\Traits\DataProviderTrait;

/**
 * This is the repository class for table "clients".
 * Class ClientRepository
 *
 * @package  App\Domain\Client\Repositories
 * @method Client []|Collection search(array $parameters = [], int $limit = null)
 * @method Client []|Collection searchActive(array $parameters = [], int $limit = null)
 * @method Client|null one(string|int|null $id, bool $active = false)
 * @method Client|null oneActive(array $params = [])
 * @method Client|null find(array $params = [])
 * @method Client|null findActive(array $params = [])
 */
class ClientRepository extends BaseEloquentRepository implements DataProviderInterface
{
    use DataProviderTrait;

    protected function query(): Builder
    {
        return $this->newQuery()->where('status', '<>', ClientStatusEnum::DELETED);
    }

    protected function modelClass(): string
    {
        return Client::class;
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
        return $this->query()->where('status', '=', ClientStatusEnum::ACTIVE);
    }

    protected function applyParameters(Builder $query, array $parameters = []): Builder
    {
        return parent::applyParameters($query, $parameters)
            ->when(Arr::get($parameters, 'telegramId'), fn(Builder $query, $value) => $query->where('telegram_id', $value))
            ->when(Arr::get($parameters, 'mailingNews'), fn(Builder $query, $value) => $query->where('mailing_news', $value))
            ->when(Arr::get($parameters, 'mailingEvents'), fn(Builder $query, $value) => $query->where('mailing_events', $value))
            ->when(Arr::get($parameters, 'mailingNewsOrEvents'), fn(Builder $query, $value) => $query->where(fn($query) => $query->where('mailing_news', $value)
                ->orWhere('mailing_events', $value)));
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
     * @param $role
     *
     * @return Client[]|Collection|array
     */
    public function getByRole($role): Collection|array
    {
        return $this->query()->where('role', $role)->get();
    }

    public function toEloquentDataProviderForEvent(Event $event): EloquentDataProvider
    {
        $query = $this->query()->join('client_event', 'client_event.client_id', '=', 'clients.id')
            ->where('client_event.event_id', $event->id);
        return new EloquentDataProvider($query);
    }

    public function allVerified()
    {
        return $this->query()->where('status', ClientStatusEnum::VERIFIED)->get();
    }
}
