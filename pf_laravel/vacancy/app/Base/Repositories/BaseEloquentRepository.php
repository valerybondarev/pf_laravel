<?php

namespace App\Base\Repositories;


use App\Base\Interfaces\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use LogicException;

abstract class BaseEloquentRepository implements RepositoryInterface
{
    protected array $keyNames     = ['id'];
    protected int   $defaultLimit = 20;

    public function __construct()
    {
        if (!is_subclass_of($this->modelClass(), Model::class)) {
            throw new LogicException('Method modelClass() of ' . static::class . ' must return ' . Model::class . ' instance');
        }
    }

    abstract protected function modelClass(): string;

    public function one(string|int|null $id, bool $active = false): Model|Builder|null
    {
        $query = $active ? $this->active() : $this->query();

        return $query
            ->where(function (Builder $query) use ($id) {
                foreach ($this->keyNames as $keyName) {
                    $query->orWhere($this->qualifyColumn($keyName), $id);
                }
            })
            ->first();
    }

    public function oneActive(string|int|null $id): Model|Builder|null
    {
        return $this->one($id, true);
    }

    public function all(bool $active = false): Collection|array
    {
        return ($active ? $this->active() : $this->query())->get();
    }

    public function allActive(): Collection|array
    {
        return $this->all(true);
    }

    public function find(array $parameters = [], bool $active = false): Model|Builder|null
    {
        return $this->applyParameters($active ? $this->active() : $this->query(), $parameters)->first();
    }

    public function findActive(array $parameters = []): Model|Builder|null
    {
        return $this->find($parameters, true);
    }

    public function search(array $parameters = [], int $limit = null, bool $active = false): Collection|array
    {
        return $this->applyParameters($active ? $this->active() : $this->query(), $parameters)->limit($limit)->get();
    }

    public function searchActive(array $parameters = [], int $limit = null): Collection|array
    {
        return $this->search($parameters, $limit, true);
    }

    public function paginate(array $parameters = [], ?int $limit = null, bool $active = false): LengthAwarePaginatorInterface|LengthAwarePaginator
    {
        return $this
            ->applyParameters($active ? $this->active() : $this->query(), $parameters)
            ->paginate($limit ?: $this->defaultLimit);
    }

    public function paginateActive(array $parameters = [], int $limit = null): LengthAwarePaginatorInterface|LengthAwarePaginator
    {
        return $this->paginate($parameters, $limit, true);
    }

    public function count(array $parameters = [], bool $active = false): int
    {
        return $this
            ->applyParameters($active ? $this->active() : $this->query(), $parameters)
            ->count();
    }

    public function countActive(array $parameters = []): int
    {
        return $this->count($parameters, true);
    }

    public function exists(array $parameters = [], bool $active = false): bool
    {
        return $this
            ->applyParameters($active ? $this->active() : $this->query(), $parameters)
            ->exists();
    }

    public function existsActive(array $parameters = []): bool
    {
        return $this->exists($parameters, true);
    }

    public function delete(array $parameters = [], bool $active = false): int
    {
        return $this
            ->applyParameters($active ? $this->active() : $this->query(), $parameters)
            ->delete();
    }

    public function deleteActive(array $parameters = []): int
    {
        return $this->delete($parameters, true);
    }

    protected function query(): Builder
    {
        return $this->newQuery();
    }

    protected function active(): Builder
    {
        return $this->query();
    }

    protected function qualifyColumn($column): string
    {
        return $this->getModelInstance()->qualifyColumn($column);
    }

    protected function applyParameters(Builder $query, array $parameters = []): Builder
    {
        foreach ($parameters as $parameter => $value) {
            if ($this->hasParameterApplier($parameter)) {
                $this->applyParameter($query, $parameter, $value);
            }
        }
        return $query;
    }

    protected function newQuery(): Builder
    {
        return $this->getModelInstance()->newQuery();
    }

    private function getModelInstance(): Model
    {
        return app($this->modelClass());
    }

    private function hasParameterApplier(string $parameter): bool
    {
        return method_exists($this, 'apply' . Str::studly($parameter));
    }

    private function applyParameter(Builder $query, string $parameter, $value)
    {
        $this->{'apply' . Str::studly($parameter)}($query, $value);
    }

}
