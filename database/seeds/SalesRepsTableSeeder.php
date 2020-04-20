<?php

use Illuminate\Database\Seeder;
use App\Models\SalesRep;

class SalesRepsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $curr_time = now();
        SalesRep::insert([
            [
                'firstname' => 'Jeff',
                'lastname' => 'Harding',
                'tel' => '6048494068',
                'cell' => '6048494068',
                'email' => 'jeffharding@ccjinternationaltrade.com',
                'created_at' => $curr_time,
                'updated_at' => $curr_time,
            ],
            [
                'firstname' => 'Keith',
                'lastname' => 'Unknown',
                'tel' => '6041234567',
                'cell' => NULL,
                'email' => NULL,
                'created_at' => $curr_time,
                'updated_at' => $curr_time,
            ],
            [
                'firstname' => 'John',
                'lastname' => 'Unknown',
                'tel' => '7781234567',
                'cell' => NULL,
                'email' => NULL,
                'created_at' => $curr_time,
                'updated_at' => $curr_time,
            ]
        ]);

        $rep = SalesRep::find(3);
        $rep->deleted_at = now();
        $rep->makeVisible('deleted_at')->save();

    }
}
