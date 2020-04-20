<?php

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::insert([
            [
                'name' => 'Leather Coin Purses',
                'price' => 8.50,
            ],
            [
                'name' => 'Keychains',
                'price' => 6.50,
            ],
            [
                'name' => 'Small Leather Wallets',
                'price' => 9.00,
            ],
            [
                'name' => 'Large Leather Wallets',
                'price' => 9.50,
            ],
            [
                'name' => 'Canada Flag Tags',
                'price' => 0.00,
            ],
            [
                'name' => 'Vancouver Tags',
                'price' => 0.00,
            ],
            [
                'name' => 'Banff Tags',
                'price' => 0.00,
            ],
        ]);
    }
}
