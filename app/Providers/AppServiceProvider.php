<?php

namespace App\Providers;

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
        $this->app->register(\App\Addons\ProductsPremoderation\ServiceProviders\AddonServiceProvider::class);
        $this->app->register(\App\Addons\ProductUsergroups\ServiceProviders\AddonServiceProvider::class);
        $this->app->register(\App\Addons\Warehouses\ServiceProviders\AddonServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
