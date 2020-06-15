<?php

namespace App\Addons\Warehouses\Behaviors\Products;

use App\Products\QueryBuilder;

trait HasAmountSplitByStock
{
    public static function bootHasAmountSplitByStock(): void
    {
        static::addGlobalScope(
            static::class,
            static function (QueryBuilder $builder) {
                $builder->leftJoin('stocks', 'stocks.product_id', '=', 'products.id');
            }
        );
    }
}