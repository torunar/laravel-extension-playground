<?php

namespace App\Providers;

use App\GraphQL\Services\GraphQLService;
use App\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\TypeResolver;
use App\Products\GraphQL\Types\ProductDescriptionType;
use App\Products\GraphQL\Types\ProductType;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class GraphQLServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->singleton(
            GraphQLService::class,
            static function (Container $app) {
                return new GraphQLService(
                    $app->make('graphql'),
                    new TypeResolver(),
                );
            }
        );
    }

    public function boot()
    {
        GraphQL::registerType(ProductType::class);
        GraphQL::registerType(ProductDescriptionType::class);
    }
}
