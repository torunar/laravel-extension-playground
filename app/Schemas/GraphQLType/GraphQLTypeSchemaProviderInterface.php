<?php

namespace App\Schemas\GraphQLType;

interface GraphQLTypeSchemaProviderInterface
{
    public static function getGraphQLTypeSchema(): GraphQLTypeSchema;
}
