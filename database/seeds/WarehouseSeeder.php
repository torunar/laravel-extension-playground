<?php

use App\Addons\Warehouses\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            $warehouse = new Warehouse();
            $warehouse->save();
        }
    }
}