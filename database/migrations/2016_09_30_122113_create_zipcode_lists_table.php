<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZipcodeListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zipcode_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('zipcode',10)->nullable();
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
        Schema::drop('zipcode_lists');
    }
}
