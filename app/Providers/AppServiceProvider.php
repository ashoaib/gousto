<?php

namespace App\Providers;

use Dingo\Api\Provider\LumenServiceProvider;
use Gousto\Providers\GoustoServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(LumenServiceProvider::class);
        $this->app->register(GoustoServiceProvider::class);
    }
}
