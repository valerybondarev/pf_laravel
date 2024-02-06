<?php

namespace App\Console\Enums;

use App\Console\Scenarios\RepositoryScenario;
use Chatway\LaravelCrudGenerator\Core\Scenarios\AdminScenario;
use Chatway\LaravelCrudGenerator\Core\Scenarios\DefaultScenario;
use Chatway\LaravelCrudGenerator\Core\Scenarios\ModelScenario;

class ScenariosEnum
{
    const DEFAULT    = 'default';
    const ADMIN      = 'admin';
    const MODEL      = 'model';
    const REPOSITORY = 'repository';

    public array $scenarios = [
        self::DEFAULT    => DefaultScenario::class,
        self::ADMIN      => AdminScenario::class,
        self::MODEL      => ModelScenario::class,
        self::REPOSITORY => RepositoryScenario::class,
    ];
}