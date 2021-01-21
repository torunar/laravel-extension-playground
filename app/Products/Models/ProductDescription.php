<?php

namespace App\Products\Models;

use App\Products\SchemaProviders\ProductDescriptionSchemaProvider;
use App\Schemas\ModelSchema\DescribedByModelSchema;
use Illuminate\Database\Eloquent\Model;

class ProductDescription extends Model
{
    use DescribedByModelSchema;

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::setModelSchema(ProductDescriptionSchemaProvider::getModelSchema());
    }
}
