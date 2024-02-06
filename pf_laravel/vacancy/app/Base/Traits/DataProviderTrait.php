<?php

namespace App\Base\Traits;

use App\Base\DataProviders\EloquentDataProvider;
use Illuminate\Database\Eloquent\Builder;

trait DataProviderTrait
{
    abstract protected function query(): Builder;

    public function toEloquentDataProvider(): EloquentDataProvider
    {
        return new EloquentDataProvider($this->query());
    }
}