<?php

use App\Products\Product;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    private $statuses = ['active', 'hidden', 'disabled'];

    private $premoderation_statuses = ['approved', 'rejected'];

    private $usergroup_ids = [[], [1], [1, 3], [2, 3], [3]];

    public function run()
    {
        $faker = Factory::create();

        for ($i = 0; $i < 200; $i++) {
            $product = new Product();
            $product->sku = $faker->word . '_' . sprintf('%03d', $i);
            $product->price = random_int(1 * 100, 20 * 100);
            $product->status = $this->statuses[array_rand($this->statuses)];
            $product->premoderation_status = $this->premoderation_statuses[array_rand($this->premoderation_statuses)];
            $product->usergroup_ids = $this->usergroup_ids[array_rand($this->usergroup_ids)];
            $product->amount = random_int(0, 99);
            $product->save();
        }
    }
}