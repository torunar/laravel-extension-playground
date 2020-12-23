<?php

namespace App\Http\Controllers;

use App\Http\Requests\DebugRequest;
use App\Products\Commands\GetAllProductsCommand;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class DebugController extends BaseController
{
    public function index(DebugRequest $request)
    {
        DB::connection()->enableQueryLog();

        $getAllProductsCommand = new GetAllProductsCommand();
        $allProducts = $getAllProductsCommand->run();

        return response()->json(
            [
                'queryLog'    => DB::connection()->getQueryLog(),
                'allProducts' => $allProducts,
            ]
        );
    }
}
