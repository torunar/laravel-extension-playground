<?php

namespace App\GraphQL\Support\Facades;

use App\GraphQL\Services\GraphQLService;
use App\Schemas\ModelSchema\ModelSchema;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \GraphQL\Type\Definition\Type type(string $type)
 * @see \App\GraphQL\Services\GraphQLService::type
 * @method static \App\GraphQL\Services\GraphQLService registerType(string $typeClass)
 * @see \App\GraphQL\Services\GraphQLService::registerType
 * @method static \App\Schemas\GraphQLTypeSchema\GraphQLTypeSchema getTypeSchemaFromModelSchema(ModelSchema $modelSchema)
 * @see \App\GraphQL\Services\GraphQLService::getTypeSchemaFromModelSchema
 * @method static \App\GraphQL\Services\GraphQLService registerQuery(string $queryClass)
 * @see \App\GraphQL\Services\GraphQLService::registerQuery
 * @method static \App\GraphQL\Services\GraphQLService registerMutation(string $mutationClass)
 * @see \App\GraphQL\Services\GraphQLService::registerMutation
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
