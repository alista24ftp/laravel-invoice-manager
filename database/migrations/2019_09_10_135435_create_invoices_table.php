<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            // basic invoice info
            $table->bigIncrements('invoice_no');
            $table->date('create_date');
            $table->string('sales_rep', 65)->nullable();
            $table->string('po_no');
            $table->string('terms');
            $table->integer('terms_period')->nullable();
            $table->string('via');
            $table->text('memo')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('paid')->default(false);

            // Company snapshot
            //$table->bigInteger('company_id');
            $table->string('company_name');
            $table->string('company_mail_addr');
            $table->string('company_mail_postal', 6);
            $table->string('company_email');
            $table->string('company_website')->nullable();
            $table->string('company_ware_addr');
            $table->string('company_ware_postal', 6);
            $table->string('company_tel', 11);
            $table->string('company_fax', 11)->nullable();
            $table->string('company_tollfree', 11)->nullable();
            $table->string('company_contact_fname', 30);
            $table->string('company_contact_lname', 30)->nullable();
            $table->string('company_contact_tel', 11);
            $table->string('company_contact_cell', 11)->nullable();
            $table->string('company_contact_email')->nullable();
            $table->string('company_tax_reg');

            // Customer snapshot
            //$table->bigInteger('customer_id');
            $table->string('bill_name');
            $table->string('bill_addr');
            $table->string('bill_prov', 2);
            $table->string('bill_city', 50);
            $table->string('bill_postal', 6);
            $table->string('ship_name');
            $table->string('ship_addr');
            $table->string('ship_prov', 2);
            $table->string('ship_city', 50);
            $table->string('ship_postal', 6);
            $table->string('customer_tel', 11);
            $table->string('customer_fax', 11)->nullable();
            $table->string('customer_contact1', 30);
            $table->string('customer_contact2', 30)->nullable();

            // other info
            $table->string('tax_description', 25);
            $table->decimal('tax_rate', 5, 3)->default(0.00);
            $table->decimal('freight', 5, 2)->nullable()->default(0.00);

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
        Schema::dropIfExists('invoices');
    }
}
