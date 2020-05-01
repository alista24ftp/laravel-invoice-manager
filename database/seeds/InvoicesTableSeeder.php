<?php

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Order;
use App\Models\PaymentProof;
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
        $this->cleanUp();
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

            $num_payment_proofs = $faker->randomElement([0, 1, 2]);
            $payment_proof_imgs = [];
            if($num_payment_proofs > 0){
                array_push($payment_proof_imgs, ['filename' => '1.png', 'ext' => '.png']);
            }
            if($num_payment_proofs > 1){
                array_push($payment_proof_imgs, ['filename' => '2.jpg', 'ext' => '.jpg']);
            }
            $curr_timestamp = time();
            $img_seed_dir_path = public_path() . "/uploads/images/seeds";
            $dest_upload_path = "/uploads/images/payment_proofs/" . date('Ym/d', $curr_timestamp);
            $dest_path = public_path() . $dest_upload_path;
            if(!file_exists($dest_path) || !is_dir($dest_path)){
                mkdir($dest_path, 0777, true);
            }
            foreach($payment_proof_imgs as $img){
                $uploaded_filename = $invoice->invoice_no . '_' . time() . '_' . Str::random(10) . $img['ext'];
                $dest_filename = $dest_path . '/' . $uploaded_filename;
                copy($img_seed_dir_path . '/' . $img['filename'], $dest_filename);
                PaymentProof::insert([
                    'invoice_no' => $invoice->invoice_no,
                    'path' => $dest_upload_path . '/' . $uploaded_filename,
                    'create_time' => now()
                ]);
            }
        });
    }

    // Delete all images within a directory and all its subdirectories
    protected function deleteAllImgs($path)
    {
        if(!file_exists($path)) return false;
        if(is_file($path) && in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg', 'png', 'jpeg', 'gif']))
            return unlink($path);
        else if(is_dir($path)){
            $subfiles = glob($path . '/*');
            foreach($subfiles as $file){
                $this->deleteAllImgs($file);
            }
            if(count(glob($path . '/*')) == 0) @rmdir($path);
            return true;
        }
    }

    // Cleanup before seeding
    protected function cleanUp()
    {
        $this->deleteAllImgs(public_path() . '/uploads/images/payment_proofs');
        $this->deleteAllImgs(public_path() . '/uploads/images/temp');
    }
}
