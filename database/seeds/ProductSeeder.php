<?php

use App\Products\Models\Product;
use App\Products\Models\ProductDescription;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    private array $statuses = ['active', 'hidden', 'disabled'];

    private array $lang_codes = ['en', 'ja'];

    public function run()
    {
        $faker = Factory::create();

        for ($i = 0; $i < 200; $i++) {
            $product = new Product();
            $product->sku = $faker->word . '_' . sprintf('%03d', $i);
            $product->status = $this->statuses[array_rand($this->statuses)];
            $product->save();

            foreach ($this->lang_codes as $lang_code) {
                $description = new ProductDescription();
                $description->lang_code = $lang_code;
                $description->product_id = $product->id;
                $description->name = implode(' ', $faker->words(3));
                $description->description = implode(PHP_EOL, $faker->paragraphs(2));
                $description->save();
            }
        }
    }
}
