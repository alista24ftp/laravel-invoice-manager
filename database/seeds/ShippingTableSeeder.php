<?php

use Illuminate\Database\Seeder;
use App\Models\Shipping;

class ShippingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shipping::insert([
            ['option' => 'DELIVERY'],
            ['option' => 'PICKUP'],
            ['option' => 'CANPAR'],
            ['option' => 'CANADA POST'],
            ['option' => 'FEDEX'],
        ]);
    }
}
