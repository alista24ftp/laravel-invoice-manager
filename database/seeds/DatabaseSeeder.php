<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CompaniesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(ShippingTableSeeder::class);
        $this->call(SalesRepsTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(TermsTableSeeder::class);
        $this->call(TaxesTableSeeder::class);
        $this->call(InvoicesTableSeeder::class);
    }
}
