<?php

namespace App\Providers;

use App\Products\Models\Product;
use App\Products\Models\ProductDescription;
use App\Schemas\ModelSchema\Attribute;
use App\Schemas\ModelSchema\ModelSchema;
use App\Schemas\ModelSchema\PrimitiveAttributeType;
use App\Schemas\ModelSchema\Relation;
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
        //
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
