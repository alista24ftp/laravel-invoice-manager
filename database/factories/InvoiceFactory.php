<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Invoice;
use App\Models\SalesRep;
use App\Models\PaymentTerm;
use App\Models\Shipping;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Tax;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Invoice::class, function (Faker $faker) {

    $datetime = $faker->dateTimeBetween('-10 years', 'now');
    $sales_rep = intval($faker->randomDigitNotNull()) % 2 == 0 ? null : SalesRep::orderByRaw('RAND()')->first();
    $company = Company::find(1);
    $term = PaymentTerm::orderByRaw('RAND()')->first();
    $via = Shipping::orderByRaw('RAND()')->first();
    $customer = Customer::orderByRaw('RAND()')->first();
    $tax = Tax::where('province', $customer->ship_prov)->first();

    return [
        'create_date' => date_format($datetime, 'Y-m-d'),
        'sales_rep' => $sales_rep ? ($sales_rep->firstname . ' ' . $sales_rep->lastname) : null,
        'po_no' => $faker->numberBetween(1000, 99999),
        'terms' => $term->option,
        'via' => $via->option,
        'memo' => intval($faker->randomDigitNotNull()) % 2 == 0 ? null : $faker->sentence(),
        'notes' => intval($faker->randomDigitNotNull()) % 2 == 0 ? null : $faker->sentence(),
        'paid' => intval($faker->randomDigitNotNull()) % 2 == 0,

        'company_id' => $company->id,
        'company_name' => $company->company_name,
        'company_mail_addr' => $company->mail_addr,
        'company_mail_postal' => $company->mail_postal,
        'company_email' => $company->email,
        'company_website' => $company->website,
        'company_ware_addr' => $company->warehouse_addr,
        'company_ware_postal' => $company->warehouse_postal,
        'company_tel' => $company->tel,
        'company_fax' => $company->fax,
        'company_tollfree' => $company->toll_free,
        'company_contact_fname' => $company->contact1_firstname,
        'company_contact_lname' => $company->contact1_lastname,
        'company_contact_tel' => $company->contact1_tel,
        'company_contact_cell' => $company->contact1_cell,
        'company_contact_email' => $company->contact1_email,
        'company_tax_reg' => $company->tax_reg,

        'customer_id' => $customer->id,
        'bill_name' => $customer->bill_name,
        'bill_addr' => $customer->bill_addr,
        'bill_prov' => $customer->bill_prov,
        'bill_city' => $customer->bill_city,
        'bill_postal' => $customer->bill_postal,
        'ship_name' => $customer->ship_name,
        'ship_addr' => $customer->ship_addr,
        'ship_prov' => $customer->ship_prov,
        'ship_city' => $customer->ship_city,
        'ship_postal' => $customer->ship_postal,
        'customer_tel' => $customer->contact1_tel,
        'customer_fax' => $customer->fax,
        'customer_contact1' => $customer->contact1_firstname,
        'customer_contact2' => $customer->contact2_firstname,

        'tax_description' => $tax->description,
        'tax_rate' => $tax->rate,
        'freight' => intval($faker->randomDigitNotNull()) < 7 ? $faker->randomFloat(2, 0, 150) : 0.00,

        'created_at' => $datetime,
        'updated_at' => $datetime,
    ];
});
