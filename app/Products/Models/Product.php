<?php

namespace App\Products\Models;

use App\Products\SchemaProviders\ProductSchemaProvider;
use App\Schemas\ModelSchema\DescribedByModelSchema;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use DescribedByModelSchema;

    public static function boot()
    {
        parent::boot();

        static::setModelSchema(ProductSchemaProvider::getModelSchema());
    }
}
