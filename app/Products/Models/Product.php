<?php

namespace App\Products\Models;

use App\Schemas\ModelSchema\Attribute;
use App\Schemas\ModelSchema\DescribedByModelSchema;
use App\Schemas\ModelSchema\ModelSchema;
use App\Schemas\ModelSchema\PrimitiveAttributeType;
use App\Schemas\ModelSchema\Relation;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use DescribedByModelSchema;

    public static function boot()
    {
        parent::boot();

        $schema = new ModelSchema();
        $schema
            ->setTableName('products')
            ->setKeyName('id')
            ->addAttribute(new Attribute('id', new PrimitiveAttributeType('int')))
            ->addAttribute(new Attribute('sku', new PrimitiveAttributeType('string')))
            ->addAttribute(new Attribute('status', new PrimitiveAttributeType('string')))
            ->addRelation(
                new Relation(
                    'product_descriptions',
                    static function (self $product) {
                        return $product->hasMany(ProductDescription::class);
                    }
                )
            );

        static::setModelSchema($schema);
    }
}
