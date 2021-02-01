<?php

namespace App\GraphQL\Support\Facades;

use App\GraphQL\Services\GraphQLService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \GraphQL\Type\Definition\Type type(string $type)
 * @see \App\GraphQL\Services\GraphQLService::type
 * @method static \App\GraphQL\Services\GraphQLService registerType(string $typeClass)
 * @see \App\GraphQL\Services\GraphQLService::registerType
 */
class GraphQL extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return GraphQLService::class;
    }
}
