<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }


    public function boot()
    {
        $this->bootDirectives();
        Validator::extend('website', function ($attribute, $value, $parameters, $validator) {
            return filter_var($value, FILTER_VALIDATE_URL);
        });
        if (\Str::contains(\Config::get('app.url'), 'https://')) {
            \URL::forceScheme('https');
        }
    }

    protected function bootDirectives()
    {
        Blade::directive('codex', function ($expression) {
            return "<?php echo Codex::render($expression); ?>";
        });

        Blade::directive('ifCodex', function ($expression) {
            return "<?php if (Codex::hasBlocks($expression)): ?>";
        });

        Blade::directive('endCodex', function () {
            return "<?php endif ?>";
        });
    }
}
