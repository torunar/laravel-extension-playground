<?php

namespace App\Schemas\GraphQLTypeSchema;

interface GraphQLTypeSchemaProviderInterface
{
    public static function getGraphQLTypeSchema(): GraphQLTypeSchema;
}
