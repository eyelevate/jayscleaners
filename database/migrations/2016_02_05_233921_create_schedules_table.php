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
        Schema::create('schedules', function (Blueprint $table) {    
            $table->increments('id');
            $table->integer('company_id', false, true)->length(11)->nullable();
            $table->integer('customer_id', false, true)->length(11)->nullable();
            $table->integer('pickup_delivery_id', false, true)->length(11)->nullable();
            $table->dateTime('dropoff_date')->nullable();
            $table->integer('dropoff_delivery_id', false, true)->length(11)->nullable();
            $table->text('special_instructions')->nullable();
            $table->string('type',20)->nullable();
            $table->string('token',8)->nullable();
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
        Schema::drop('schedules');
    }
}
