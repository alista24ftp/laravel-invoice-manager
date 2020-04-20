<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Customer;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

$factory->define(Customer::class, function (Faker $faker) {
    //$provinces = ['BC', 'AB', 'SK', 'MB', 'ON', 'QC', 'NB', 'NS', 'PE', 'NL', 'YT', 'NT', 'NU'];
    //$prov = Arr::random($provinces);
    return [
        'bill_name' => $faker->company(),
        'bill_addr' => $faker->streetAddress(),
        'bill_prov' => $faker->provinceAbbr(),
        'bill_city' => $faker->city(),
        'bill_postal' => preg_replace('/[ \-]/', '', $faker->postcode()),
        'ship_name' => $faker->company(),
        'ship_addr' => $faker->streetAddress(),
        'ship_prov' => $faker->provinceAbbr(),
        'ship_city' => $faker->city(),
        'ship_postal' => preg_replace('/[ \-]/', '', $faker->postcode()),
        'email' => $faker->email(),
        'fax' => $faker->numerify('##########'),
        'contact1_firstname' => $faker->firstName(),
        'contact1_lastname' => $faker->lastName(),
        'contact1_tel' => $faker->numerify('##########'),
        'contact1_cell' => $faker->numerify('##########'),
    ];
});
