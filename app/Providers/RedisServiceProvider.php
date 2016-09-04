<?php namespace App\Providers;

use App\Services\RedisDatabase;
use Illuminate\Redis\RedisServiceProvider as ServiceProvider;

class RedisServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('redis', function ($app) {
            return new RedisDatabase($app['config']['database.redis']);
        });
    }
}