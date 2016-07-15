<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrintersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id', false, true)->length(11)->nullable();
            $table->string('name',100)->nullable();
            $table->string('model',100)->nullable();
            $table->string('nick_name',100)->nullable();
            $table->string('vendor_id',20)->nullable();
            $table->string('product_id',20)->nullable();
            $table->tinyInteger('type', false, true)->nullable();
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
        Schema::drop('printers');
    }
}
