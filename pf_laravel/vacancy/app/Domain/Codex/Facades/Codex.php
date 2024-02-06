<?php


namespace App\Domain\Codex\Facades;


use Illuminate\Support\Facades\Facade;

class Codex extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'codex';
    }
}
