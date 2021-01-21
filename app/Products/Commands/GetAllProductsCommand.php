<?php

namespace App\Products\Commands;

use App\Products\Models\Product;
use Illuminate\Support\Collection;

class GetAllProductsCommand
{
    public function run(array $with = []): Collection
    {
        return Product::with($with)->get();
    }
}
