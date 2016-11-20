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
            $table->integer('user_id', false, true)->length(11)->nullable();
            $table->integer('company_id', false, true)->length(11)->nullable();
            $table->string('username',50)->nullable();
            $table->string('first_name',50)->nullable();
            $table->string('last_name',50)->nullable();
            $table->string('street',200)->nullable();
            $table->string('suite',20)->nullable();
            $table->string('city',50)->nullable();
            $table->string('state',2)->nullable();
            $table->string('zipcode',10)->nullable();
            $table->string('email')->nullable();
            $table->string('phone',15)->nullable();
            $table->string('intercom',20)->nullable();
            $table->string('concierge_name',50)->nullable();
            $table->string('concierge_number',20)->nullable();
            $table->text('special_instructions')->nullable();
            $table->string('shirt_old',10)->nullable();  
            $table->tinyInteger('shirt',false, true)->length(1)->nullable();    
            $table->boolean('delivery')->nullable();
            $table->string('profile_id',20)->nullable();
            $table->tinyInteger('payment_status',false, true)->length(1)->nullable();
            $table->string('payment_id',11)->nullable();
            $table->string('token',8)->nullable();
            $table->string('api_token',20)->nullable();
            $table->tinyInteger('reward_status',false, true)->length(1)->nullable();
            $table->integer('reward_points', false, true)->length(11)->nullable();
            $table->boolean('account')->nullable();
            $table->decimal('account_total',11,2)->nullable();
            $table->decimal('credits',11,2)->nullable();
            $table->string('starch_old',10)->nullable();
            $table->tinyInteger('starch',false, true)->length(1)->nullable();
            $table->text('important_memo')->nullable();
            $table->text('invoice_memo')->nullable();
            $table->string('password', 60)->nullable();
            $table->tinyInteger('role_id', false, true)->length(1)->nullable();
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
