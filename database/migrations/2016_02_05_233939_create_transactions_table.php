<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id', false, true)->length(11)->nullable();
            $table->integer('customer_id', false, true)->length(11)->nullable();
            $table->integer('schedule_id', false, true)->length(11)->nullable();
            $table->decimal('pretax',11,2)->nullable();
            $table->decimal('tax',11,2)->nullable();
            $table->decimal('aftertax',11,2)->nullable();
            $table->decimal('discount',11,2)->nullable();
            $table->decimal('total',11,2)->nullable();
            $table->text('invoices')->nullable();
            $table->tinyInteger('type', false, true)->nullable();
            $table->tinyInteger('last_four', false, true)->length(4)->nullable();
            $table->decimal('tendered',11,2)->nullable();
            $table->integer('transaction_id', false, true)->length(25)->nullable();
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
        Schema::drop('transactions');
    }
}
