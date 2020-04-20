<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bill_name')->index();
            $table->string('bill_addr');
            $table->string('bill_prov', 50);
            $table->string('bill_city', 50);
            $table->string('bill_postal', 6);
            $table->string('ship_name')->index();
            $table->string('ship_addr');
            $table->string('ship_prov', 50);
            $table->string('ship_city', 50);
            $table->string('ship_postal', 6);
            $table->string('email')->nullable();
            $table->string('fax', 11)->nullable();
            $table->string('contact1_firstname', 30);
            $table->string('contact1_lastname', 30)->nullable();
            $table->string('contact1_tel', 11);
            $table->string('contact1_cell', 11)->nullable();
            $table->string('contact2_firstname', 30)->nullable();
            $table->string('contact2_lastname', 30)->nullable();
            $table->string('contact2_tel', 11)->nullable();
            $table->string('contact2_cell', 11)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
