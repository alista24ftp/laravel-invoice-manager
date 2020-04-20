<?php

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Order;
use Faker\Generator as Faker;

class InvoicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Faker::class);
        $invoices = factory(Invoice::class)->times(100)->make();
        Invoice::insert($invoices->toArray());

        Invoice::all()->each(function($invoice) use ($faker){
            $num_main_prods = $faker->randomElement([1,2,3,4]);
            $num_sec_prods = $faker->randomElement([0,1,2]);
            $main_products = Product::where('price', '>', 5)->orderByRaw('RAND()')->take($num_main_prods)->get();
            $sec_products = $num_sec_prods ? Product::where('price', '<=', '5')->orderByRaw('RAND()')->take($num_sec_prods)->get() : collect();
            $products = $main_products->concat($sec_products)->all();
            foreach($products as $product){
                $price = floatval($product->price);
                $quantity = intval($faker->numberBetween(20, 400));
                $discount = intval($faker->numberBetween(1,100)) > 90 ? round($price * 0.05, 2) : null;
                Order::insert([
                    'invoice_no' => $invoice->invoice_no,
                    'product' => $product->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'discount' => $discount,
                    'total' => ($price * $quantity) - $discount,
                ]);
            }
        });
    }
}
