<?php

namespace App\Products\GraphQL\Types;

use App\Products\Models\ProductDescription;
use App\Products\SchemaProviders\ProductDescriptionSchemaProvider;
use Rebing\GraphQL\Support\Type;

class ProductDescriptionType extends Type
{
    const ID = 'ProductDescription';

    protected $attributes = [
        'name'        => self::ID,
        'description' => 'A product description',
        'model'       => ProductDescription::class,
    ];

    public function fields(): array
    {
        return ProductDescriptionSchemaProvider::getGraphQLTypeSchema()->getFieldsDefinition();
    }
}
