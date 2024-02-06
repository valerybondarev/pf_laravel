<?php

namespace App\Domain\Catalog\Repositories;

use App\Base\DataProviders\EloquentDataProvider;
use App\Base\Interfaces\DataProviderInterface;
use App\Base\Repositories\BaseEloquentRepository;
use App\Domain\Catalog\Entities\CatalogImportJob;
use App\Domain\Catalog\Enums\CatalogImportJobStatus;
use Illuminate\Database\Eloquent\Collection;

class CatalogImportJobRepository extends BaseEloquentRepository implements DataProviderInterface
{
    protected function modelClass(): string
    {
        return CatalogImportJob::class;
    }

    /**
     * @return CatalogImportJob[]|Collection|array
     */
    public function allWait(): Collection|array
    {
        return $this->query()->where(['status' => CatalogImportJobStatus::WAIT])->get();
    }

    public function toEloquentDataProvider(): EloquentDataProvider
    {
        return new EloquentDataProvider($this->query());
    }
}
