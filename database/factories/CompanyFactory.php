<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'company_name' => 'CCJ INTERNATIONAL TRADE LTD.',
        'mail_addr' => '1180 Waverley Ave, Vancouver BC, Canada',
        'mail_postal' => 'V5W2C3',
        'warehouse_addr' => '130-1211 Valmont Way, Richmond BC, Canada',
        'warehouse_postal' => 'V6V1Y3',
        'email' => 'ccjinternationaltrade@gmail.com',
        'website' => 'www.ccjinternationaltrade.com',
        'tax_reg' => 'GST#822998142',
        'tel' => '6042219996',
        'fax' => '6046768675',
        'toll_free' => '18665182199',
        'contact1_firstname' => 'Helen',
        'contact1_lastname' => 'Wang',
        'contact1_tel' => '6045518668',
        'contact1_cell' => '6045518668',
    ];
});
