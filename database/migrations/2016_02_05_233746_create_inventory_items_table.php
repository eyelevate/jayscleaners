<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id', false, true)->length(11)->nullable();
            $table->integer('inventory_id', false, true)->length(11)->nullable();
            $table->string('name',50)->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('tags', false, true)->nullable();
            $table->tinyInteger('ordered', false, true)->nullable();
            $table->decimal('price',11,2)->nullable();
            $table->string('image',150)->nullable();
            $table->integer('status', false, true)->nullable();
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
        Schema::drop('inventory_items');
    }
}
