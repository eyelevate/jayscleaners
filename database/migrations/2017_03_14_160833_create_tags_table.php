<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('tags');
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50)->nullable();
            $table->string('rfid',50)->nullable();
            $table->string('barcode',50)->nullable();
            $table->integer('location_id', false, true)->length(11)->nullable();
            $table->integer('company_id', false, true)->length(11)->nullable();
            $table->integer('invoice_id', false, true)->length(11)->nullable();
            $table->integer('invoice_item_id', false, true)->length(11)->nullable();
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
        Schema::drop('tags');
    }
}
