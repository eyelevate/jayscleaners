<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('addresses');
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true)->length(11)->nullable();
            $table->string('name',50)->nullable();
            $table->string('street',100)->nullable();
            $table->string('suite',20)->nullable();
            $table->string('city',50)->nullable();
            $table->string('state',20)->nullable();
            $table->string('zipcode',10)->nullable();
            $table->string('concierge_name',100)->nullable();
            $table->string('concierge_number',100)->nullable();
            $table->boolean('primary_address')->nullable();
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
        Schema::drop('addresses');
    }
}
