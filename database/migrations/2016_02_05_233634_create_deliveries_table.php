<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id', false, true)->length(11)->nullable();
            $table->string('route_name',50)->nullable();
            $table->string('day',50)->nullable();
            $table->string('limit',11)->nullable();
            $table->string('start_time',25)->nullable();
            $table->string('end_time',25)->nullable();
            $table->text('zipcode')->nullable();
            $table->text('blackout')->nullable();
            $table->tinyInteger('status',false, true)->length(1)->nullable();
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
        Schema::drop('deliveries');
    }
}
