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
        $table->increments('id');
        $table->integer('company_id');
        $table->string('name',100)->nullable();
        $table->integer('points');
        $table->decimal('discount',6,4)->nullable();
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
        Schema::drop('rewards');
    }
}
