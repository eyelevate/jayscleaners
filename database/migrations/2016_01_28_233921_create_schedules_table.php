<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
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
        $table->dateTime('pickup_date')->nullable();
        $table->integer('pickup_delivery_id');
        $table->dateTime('dropoff_date')->nullable();
        $table->integer('dropoff_delivery_id');
        $table->text('special_instructions')->nullable();
        $table->string('type',20)->nullable();
        $table->string('token',8)->nullable();
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
        Schema::drop('schedules');
    }
}
