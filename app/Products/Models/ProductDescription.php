<?php

namespace App\Products\Models;

use App\Schemas\ModelSchema\Attribute;
use App\Schemas\ModelSchema\DescribedByModelSchema;
use App\Schemas\ModelSchema\ModelSchema;
use App\Schemas\ModelSchema\PrimitiveAttributeType;
use App\Schemas\ModelSchema\Relation;
use Illuminate\Database\Eloquent\Model;

class ProductDescription extends Model
{
    use DescribedByModelSchema;

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        $schema = (new ModelSchema())
            ->setTableName('product_descriptions')
            ->setKeyName('id')
            ->addAttribute(new Attribute('id', new PrimitiveAttributeType('int')))
            ->addAttribute(new Attribute('name', new PrimitiveAttributeType('string')))
            ->addAttribute(new Attribute('description', new PrimitiveAttributeType('string')))
            ->addAttribute(new Attribute('lang_code', new PrimitiveAttributeType('string')))
            ->addRelation(
                new Relation(
                    'product',
                    static function (self $productDescription) {
                        $productDescription->hasOne(Product::class);
                    }
                )
            );

        static::setModelSchema($schema);
    }
}
