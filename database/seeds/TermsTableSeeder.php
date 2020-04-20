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
            ['option' => 'CASH'],
            ['option' => 'CHEQUE'],
            ['option' => 'PAYPAL'],
            ['option' => 'WECHAT'],
            ['option' => 'MASTERCARD'],
            ['option' => 'VISA'],
            ['option' => 'ETRANSFER'],
            ['option' => '15DAYS'],
            ['option' => '30DAYS'],
            ['option' => '60DAYS'],
        ]);
    }
}
