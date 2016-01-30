<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table->increments('id');
        $table->string('url',100)->nullable();
        $table->tinyInteger('relationship')->nullable();
        $table->string('page_name',100)->nullable();
        $table->integer('parent_id');
        $table->string('title',150)->nullable();
        $table->text('keywords')->nullable();
        $table->text('description')->nullable();
        $table->string('layout',50)->nullable();
        $table->tinyInteger('menu_id')->nullable();
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
        Schema::drop('pages');
    }
}
