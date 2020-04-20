<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_name');
            $table->string('mail_addr');
            $table->string('mail_postal', 6);
            $table->string('warehouse_addr');
            $table->string('warehouse_postal', 6);
            $table->string('email');
            $table->string('website')->nullable();
            $table->string('tax_reg');
            $table->string('tel', 11);
            $table->string('fax', 11)->nullable();
            $table->string('toll_free', 11)->nullable();
            $table->string('contact1_firstname', 30);
            $table->string('contact1_lastname', 30)->nullable();
            $table->string('contact1_tel', 11);
            $table->string('contact1_ext', 6)->nullable();
            $table->string('contact1_email')->nullable();
            $table->string('contact1_cell', 11)->nullable();
            $table->string('contact2_firstname', 30)->nullable();
            $table->string('contact2_lastname', 30)->nullable();
            $table->string('contact2_tel', 11)->nullable();
            $table->string('contact2_ext', 6)->nullable();
            $table->string('contact2_email')->nullable();
            $table->string('contact2_cell', 11)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
