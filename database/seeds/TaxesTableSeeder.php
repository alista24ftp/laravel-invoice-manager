<?php

use Illuminate\Database\Seeder;
use App\Models\Tax;

class TaxesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tax::insert([
            /*
            [
                'province' => '',
                'description' => 'No Tax',
                'rate' => '0.00',
            ],*/
            [
                'province' => 'BC',
                'description' => 'GST+PST',
                'rate' => '12.00',
            ],
            [
                'province' => 'AB',
                'description' => 'GST',
                'rate' => '5.00',
            ],
            [
                'province' => 'SK',
                'description' => 'GST+PST',
                'rate' => '11.00',
            ],
            [
                'province' => 'MB',
                'description' => 'GST+PST',
                'rate' => '12.00',
            ],
            [
                'province' => 'ON',
                'description' => 'HST',
                'rate' => '13.00',
            ],
            [
                'province' => 'QC',
                'description' => 'GST+QST',
                'rate' => '14.975',
            ],
            [
                'province' => 'NB',
                'description' => 'HST',
                'rate' => '15.00',
            ],
            [
                'province' => 'NS',
                'description' => 'HST',
                'rate' => '15.00',
            ],
            [
                'province' => 'PE',
                'description' => 'HST',
                'rate' => '15.00',
            ],
            [
                'province' => 'NL',
                'description' => 'HST',
                'rate' => '15.00',
            ],
            [
                'province' => 'YT',
                'description' => 'GST',
                'rate' => '5.00',
            ],
            [
                'province' => 'NT',
                'description' => 'GST',
                'rate' => '5.00',
            ],
            [
                'province' => 'NU',
                'description' => 'GST',
                'rate' => '5.00',
            ],
        ]);
    }
}
