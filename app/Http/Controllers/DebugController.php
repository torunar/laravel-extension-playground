<?php

namespace App\Http\Controllers;

use App\Products\Commands\GetAllProductsCommand;
use App\Products\Commands\GetVisibleProductsCommand;
use App\Products\Product;
use App\User;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class DebugController extends BaseController
{
    public function index()
    {
        DB::connection()->enableQueryLog();

        $get_all_products_command = new GetAllProductsCommand();
        $all_products = $get_all_products_command->run();

        $get_visible_products_command = new GetVisibleProductsCommand(new User([2]));
        $visible_products = $get_visible_products_command->run();

        $validation_result = null;
        if (!$visible_products->isEmpty()) {
            /** @var Product $sample_product */
            $sample_product = $visible_products->random();
            $validation_result = $sample_product->validate();
        }

        return response()->json(
            [
                'query'            => DB::connection()->getQueryLog(),
                'validation'       => $validation_result,
                'visible_products' => $visible_products,
                'all_products'     => $all_products,
            ]
        );
    }
}