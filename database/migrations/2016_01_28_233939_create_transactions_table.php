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
        $table->increments('id');
        $table->integer('company_id');
        $table->integer('customer_id');
        $table->integer('schedule_id');
        $table->decimal('pretax',11,2)->nullable();
        $table->decimal('tax',11,2)->nullable();
        $table->decimal('aftertax',11,2)->nullable();
        $table->decimal('discount',11,2)->nullable();
        $table->decimal('total',11,2)->nullable();
        $table->text('invoices')->nullable();
        $table->tinyInteger('type')->nullable();
        $table->tinyInteger('last_four',4)->nullable();
        $table->decimal('tendered',11,2)->nullable();
        $table->integer('transaction_id',25);
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
        Schema::drop('transactions');
    }
}
