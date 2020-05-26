<?php

use Illuminate\Database\Seeder;
use App\Models\PaymentTerm;

class TermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentTerm::insert([
            ['option' => 'CASH', 'period' => null],
            ['option' => 'CHEQUE', 'period' => null],
            ['option' => 'PAYPAL', 'period' => null],
            ['option' => 'WECHAT', 'period' => null],
            ['option' => 'MASTERCARD', 'period' => null],
            ['option' => 'VISA', 'period' => null],
            ['option' => 'ETRANSFER', 'period' => null],
            ['option' => '15DAYS', 'period' => 15],
            ['option' => '30DAYS', 'period' => 30],
            ['option' => '60DAYS', 'period' => 60],
        ]);
    }
}
