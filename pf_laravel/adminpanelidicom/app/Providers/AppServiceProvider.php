<?php

namespace App\Providers;

use App\Services\FileService;
use App\Services\StepStatusService;
use Illuminate\Support\ServiceProvider;
use Storage;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->singleton(FileService::class, fn() => new FileService);
        $this->app->singleton(StepStatusService::class, fn() => new StepStatusService);
    }
}
