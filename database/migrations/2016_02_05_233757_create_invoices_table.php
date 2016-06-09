<?php

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
            $table->increments('id');
            $table->integer('invoice_id', false, true)->length(11)->nullable();
            $table->integer('company_id', false, true)->length(11)->nullable();
            $table->integer('customer_id', false, true)->length(11)->nullable();
            $table->text('items')->nullable();
            $table->tinyInteger('quantity', false, true)->nullable();
            $table->decimal('pretax',11,2)->nullable();
            $table->decimal('tax',11,2)->nullable();
            $table->integer('reward_id', false, true)->length(11)->nullable();
            $table->integer('discount_id', false, true)->length(11)->nullable();
            $table->decimal('total',11,2)->nullable();
            $table->string('rack', 10)->nullable();
            $table->dateTime('rack_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->text('memo')->nullable();
            $table->integer('transaction_id', false, true)->length(11)->nullable();
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
        Schema::drop('invoices');
    }
}
