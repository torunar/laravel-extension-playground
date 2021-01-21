<?php

namespace App\Schemas\ModelSchema;

interface ModelSchemaProviderInterface
{
    public static function getModelSchema(): ModelSchema;
}
