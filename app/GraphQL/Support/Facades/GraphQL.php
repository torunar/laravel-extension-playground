<?php

namespace App\GraphQL\Support\Facades;

use App\GraphQL\Services\GraphQLService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static type(string $type): \GraphQL\Type\Definition\Type
 * @see \App\GraphQL\Services\GraphQLService::type
 * @method static registerType(string $typeClass): \App\GraphQL\Services\GraphQLService
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
