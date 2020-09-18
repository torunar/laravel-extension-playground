<?php

namespace App\Providers;

use App\Products\Models\Product;
use App\Products\Models\ProductDescription;
use App\Schemas\ModelSchema\Attribute;
use App\Schemas\ModelSchema\ModelSchema;
use App\Schemas\ModelSchema\PrimitiveAttributeType;
use App\Schemas\ModelSchema\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $productSchema = new ModelSchema();
        $productSchema
            ->setTableName('products')
            ->setKeyName('id')
            ->addAttribute(new Attribute('id', new PrimitiveAttributeType('int')))
            ->addAttribute(new Attribute('sku', new PrimitiveAttributeType('string')))
            ->addAttribute(new Attribute('status', new PrimitiveAttributeType('string')))
            ->addRelation(
                new Relation(
                    'product_descriptions',
                    static function (Product $product) {
                        return $product->hasMany(ProductDescription::class);
                    }
                )
            );
        Product::$modelSchema = $productSchema;

        $productDescriptionSchema = new ModelSchema();
        $productDescriptionSchema
            ->setTableName('product_descriptions')
            ->setKeyName('id')
            ->addAttribute(new Attribute('id', new PrimitiveAttributeType('int')))
            ->addAttribute(new Attribute('name', new PrimitiveAttributeType('string')))
            ->addAttribute(new Attribute('description', new PrimitiveAttributeType('string')))
            ->addAttribute(new Attribute('lang_code', new PrimitiveAttributeType('string')))
            ->addRelation(
                new Relation(
                    'product',
                    static function (ProductDescription $productDescription) {
                        $productDescription->hasOne(Product::class);
                    }
                )
            );
        ProductDescription::$modelSchema = $productDescriptionSchema;
    }
}
