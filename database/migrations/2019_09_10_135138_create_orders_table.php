<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('invoice_no')->unsigned()->index();
            /*
            $table->foreign('invoice_no')->references('invoice_no')->on('invoices')
                ->onDelete('cascade')
                ->onUpdate('cascade');*/
            $table->string('product');
            $table->decimal('price', 7, 2)->default(0.00);
            $table->integer('quantity')->default(1);
            $table->decimal('discount', 9, 2)->nullable()->default(0.00);
            $table->decimal('total', 9, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
