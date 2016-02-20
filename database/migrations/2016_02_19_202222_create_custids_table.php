<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custids', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id', false, true)->length(11)->nullable();
            $table->text('mark')->nullable();
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
        Schema::drop('custids');
    }
}
