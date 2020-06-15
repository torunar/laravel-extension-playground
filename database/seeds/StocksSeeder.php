<?php

use App\Addons\Warehouses\Stock;
use App\Addons\Warehouses\Warehouse;
use App\Products\Product;
use Illuminate\Database\Seeder;

class StocksSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all()->random(50);
        /** @var Product $product */
        foreach ($products as $product) {
            $warehouses = Warehouse::all()->random(3);
            /** @var Warehouse $warehouse */
            foreach ($warehouses as $warehouse) {
                $stock = new Stock();
                $stock->amount = random_int(0, 99);
                $stock->product()->associate($product);
                $stock->warehouse()->associate($warehouse);
                $stock->save();
            }
            $product->has_amount_split_by_stock = 1;
            $product->amount = $product->stocks()->sum('amount');
            $product->save();
        }
    }
}