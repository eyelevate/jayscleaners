<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id', false, true)->length(11)->nullable();
            $table->string('name',150)->nullable();
            $table->tinyInteger('tier', false, true)->length(1)->nullable();
            $table->string('url',150)->nullable();
            $table->tinyInteger('orders', false, true)->nullable();
            $table->string('icon',50)->nullable();
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
        Schema::drop('menu_items');
    }
}
