<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id', false, true)->length(11)->nullable();
            $table->text('inventory_id')->nullable();
            $table->text('inventory_item_id')->nullable();
            $table->string('name',50)->nullable();
            $table->tinyInteger('type', false, true)->nullable();
            $table->decimal('discount',9,2)->nullable();
            $table->float('rate',6,4)->nullable();
            $table->string('end_time',25)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->tinyInteger('status', false, true)->length(1)->nullable();
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
        Schema::drop('discounts');
    }
}
