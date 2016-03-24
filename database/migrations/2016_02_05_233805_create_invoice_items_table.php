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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id', false, true)->length(11)->nullable();
            $table->integer('item_id',false,true)->length(11)->nullable();
            $table->integer('company_id', false, true)->length(11)->nullable();
            $table->integer('customer_id', false, true)->length(11)->nullable();
            $table->tinyInteger('quantity', false, true)->nullable();
            $table->tinyInteger('color', false, true)->nullable();
            $table->text('memo')->nullable();
            $table->decimal('pretax',11,2)->nullable();
            $table->decimal('tax',11,2)->nullable();
            $table->decimal('total',11,2)->nullable();
            $table->tinyInteger('status', false, true)->nullable();
            $table->softDeletes();
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
        Schema::drop('invoice_items');
    }
}
