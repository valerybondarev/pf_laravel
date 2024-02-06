<?php

namespace App\Base\Interfaces;

use App\Base\DataProviders\EloquentDataProvider;

interface DataProviderInterface
{
    public function one(string|int|null $id);

    public function toEloquentDataProvider(): EloquentDataProvider;
}
