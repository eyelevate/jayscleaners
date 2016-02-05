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
        Schema::create('reward_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reward_id', false, true)->length(11)->nullable();
            $table->integer('transaction_id', false, true)->length(11)->nullable();
            $table->integer('customer_id', false, true)->length(11)->nullable();
            $table->integer('employee_id', false, true)->length(11)->nullable();
            $table->integer('company_id', false, true)->length(11)->nullable();
            $table->tinyInteger('type', false, true)->length(11)->nullable();
            $table->integer('points', false, true)->length(11)->nullable();
            $table->tinyInteger('credited', false, true)->nullable();
            $table->tinyInteger('reduced', false, true)->nullable();
            $table->integer('running_total', false, true)->length(11)->nullable();
            $table->tinyInteger('reason', false, true)->nullable();
            $table->string('name',100)->nullable();
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
        Schema::drop('reward_transactions');
    }
}
