<?php

namespace App\Products\Commands;

use App\Products\Models\Product;
use Illuminate\Support\Collection;

class GetAllProductsCommand
{
    public function run(): Collection
    {
        return Product::with('product_descriptions')->get();
    }
}
