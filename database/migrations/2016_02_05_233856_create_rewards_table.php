<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewards', function (Blueprint $table) {        
            $table->increments('id');
            $table->integer('company_id', false, true)->length(11)->nullable();
            $table->string('name',100)->nullable();
            $table->integer('points', false, true)->length(11)->nullable();
            $table->decimal('discount',6,4)->nullable();
            $table->tinyInteger('status', false, true)->length(11)->nullable();
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
        Schema::drop('rewards');
    }
}
