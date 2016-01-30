<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRewardTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table->increments('id');
        $table->integer('reward_id');
        $table->integer('transaction_id');
        $table->integer('customer_id');
        $table->integer('employee_id');
        $table->integer('company_id');
        $table->tinyInteger('type')->nullable();
        $table->integer('points');
        $table->tinyInteger('credited')->nullable();
        $table->tinyInteger('reduced')->nullable();
        $table->integer('running_total');
        $table->tinyInteger('reason')->nullable();
        $table->string('name',100)->nullable();
        $table->integer('points');
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
        Schema::drop('reward_transactions');
    }
}
