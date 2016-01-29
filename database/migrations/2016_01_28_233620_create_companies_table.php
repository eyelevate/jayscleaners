<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table->increments('id');
        $table->string('name',100)->nullable();
        $table->string('street',250)->nullable();
        $table->string('city',100)->nullable();
        $table->string('state',2)->nullable();
        $table->string('zip',10)->nullable();
        $table->string('phone',20)->nullable();
        $table->string('email',100)->nullable();
        $table->integer('owner_id');
        $table->timestamps();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('companies');
    }
}
