<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('company_id', false, true)->length(11)->nullable();
            $table->text('shirt_codes')->nullable();
            $table->string('username',50)->nullable();
            $table->string('first_name',50)->nullable();
            $table->string('last_name',50)->nullable();
            $table->string('name',50)->nullable();
            $table->dateTime('birthdate')->nullable();
            $table->string('contact_address',200)->nullable();
            $table->string('contact_suite',20)->nullable();
            $table->string('contact_city',50)->nullable();
            $table->string('contact_state',2)->nullable();
            $table->string('contact_phone',15)->nullable();
            $table->string('contact_zipcode',10)->nullable();
            $table->string('contact_intercom',20)->nullable();
            $table->text('special_instructions')->nullable();
            $table->string('shirt',10)->nullable();
            $table->boolean('delivery')->nullable();
            $table->string('profile_id',20)->nullable();
            $table->tinyInteger('payment_status',false, true)->length(1)->nullable();
            $table->string('payment_id',11)->nullable();
            $table->string('token',8)->nullable();
            $table->string('api_token',20)->nullable();
            $table->tinyInteger('reward_status',false, true)->length(1)->nullable();
            $table->integer('reward_points', false, true)->length(11)->nullable();
            $table->boolean('account')->nullable();
            $table->string('starch',10)->nullable();
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->tinyInteger('role_id', false, true)->length(1)->nullable();
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
