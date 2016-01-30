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
        $table->increments('id');
        $table->integer('company_id');
        $table->integer('customer_id');
        $table->text('items')->nullable();
        $table->integer('quantity');
        $table->tinyInteger('tags')->nullable();
        $table->decimal('pretax',11,2)->nullable();
        $table->decimal('tax',11,2)->nullable();
        $table->integer('reward_id');
        $table->integer('discount_id');
        $table->decimal('total',11,2)->nullable();
        $table->integer('rack')->nullable();
        $table->dateTime('rack_date')->nullable();
        $table->dateTime('due_date')->nullable();
        $table->text('memo')->nullable();
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
        Schema::drop('invoices');
    }
}
