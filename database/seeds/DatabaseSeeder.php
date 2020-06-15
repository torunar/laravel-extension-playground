<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(ProductSeeder::class);
         $this->call(WarehouseSeeder::class);
         $this->call(StocksSeeder::class);
    }
}
