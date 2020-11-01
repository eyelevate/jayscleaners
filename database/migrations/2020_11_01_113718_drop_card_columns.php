<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropCardColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('cards');
        Schema::create('cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id', false, true)->length(11)->nullable();
            $table->integer('user_id', false, true)->length(11)->nullable();
            $table->integer('profile_id', false, true)->length(11)->nullable();
            $table->integer('payment_id', false, true)->length(11)->nullable();
            $table->integer('root_payment_id', false, true)->length(11)->nullable();
            $table->string('street',200)->nullable();
            $table->string('suite',20)->nullable();
            $table->string('city',50)->nullable();
            $table->string('zipcode',10)->nullable();
            $table->string('state',10)->nullable();
            $table->string('exp_month', 2)->nullable();
            $table->string('exp_year', 4)->nullable();
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
        Schema::drop('cards');
    }
}
