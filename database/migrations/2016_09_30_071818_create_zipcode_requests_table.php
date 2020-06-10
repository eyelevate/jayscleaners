<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZipcodeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('zipcode_requests');
        Schema::create('zipcode_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('zipcode',10)->nullable();
            $table->string('name',50)->nullable();
            $table->string('email',150)->nullable();
            $table->text('comment')->nullable();
            $table->string('ip',20)->nullable();
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
        Schema::drop('zipcode_requests');
    }
}
