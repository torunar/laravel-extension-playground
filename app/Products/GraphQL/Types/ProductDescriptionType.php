<?php

namespace App\Products\GraphQL\Types;

use App\Products\Models\ProductDescription;
use App\Products\SchemaProviders\ProductDescriptionSchemaProvider;
use Rebing\GraphQL\Support\Type;

class ProductDescriptionType extends Type
{
    protected $attributes = [
        'name'        => 'product_description',
        'description' => 'A product description',
        'model'       => ProductDescription::class,
    ];

    public function fields(): array
    {
        return ProductDescriptionSchemaProvider::getGraphQLTypeSchema()->getFieldsDefinition();
    }
}
