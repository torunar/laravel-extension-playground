<?php

namespace App\Products\GraphQL\Types;

use App\Products\Models\Product;
use App\Products\SchemaProviders\ProductSchemaProvider;
use Rebing\GraphQL\Support\Type;

class ProductType extends Type
{
    const ID = 'Product';

    protected $attributes = [
        'name'        => self::ID,
        'description' => 'A product',
        'model'       => Product::class,
    ];

    public function fields(): array
    {
        return ProductSchemaProvider::getGraphQLTypeSchema()->getFieldsDefinition();
    }
}
