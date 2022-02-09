<?php

namespace App\Providers;

use App\Services\contracts\LionParcelServiceContract;
use App\Services\contracts\RajaOngkirServiceContract;
use App\Services\LionParcelService;
use App\Services\RajaOngkirService;
use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(LionParcelServiceContract::class, LionParcelService::class);
        $this->app->singleton(RajaOngkirServiceContract::class, RajaOngkirService::class);
    }
}
