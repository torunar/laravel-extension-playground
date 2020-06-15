<?php

namespace App\Products\Commands;

use App\Products\Events\GetAllProductsEvent;
use App\Products\Product;
use Illuminate\Support\Collection;

class GetAllProductsCommand
{
    public function run(): Collection
    {
        /** @var \App\Products\QueryBuilder $query_builder */
        $query_builder = Product::query();

        event(new GetAllProductsEvent($query_builder));

        return $query_builder->get();
    }
}