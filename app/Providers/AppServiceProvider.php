<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // if ($this->app->environment() === 'local') {
        //     $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        // }

        // if (\env('APP_DEBUGBAR_ENABLE') === true) {
        //     $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        // }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }

}
