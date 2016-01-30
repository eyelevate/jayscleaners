<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table->increments('id');
        $table->integer('reference_id');
        $table->integer('invoice_id');
        $table->integer('company_id');
        $table->integer('customer_id');
        $table->integer('quantity');
        $table->text('colors')->nullable();
        $table->decimal('pretax',11,2)->nullable();
        $table->decimal('tax',11,2)->nullable();
        $table->decimal('total',11,2)->nullable();
        $table->tinyInteger('status')->nullable();
        $table->softDeletes();
        $table->timestamps();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('invoice_items');
    }
}
