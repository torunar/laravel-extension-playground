<?php

namespace App\Providers;

use App\Validation\StrictModelValidationService;
use App\Validation\ModelValidationServiceInterface;
use Illuminate\Support\ServiceProvider;

class ModelValidationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ModelValidationServiceInterface::class, fn($app) => new StrictModelValidationService());
    }
}