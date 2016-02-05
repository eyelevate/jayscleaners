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
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url',100)->nullable();
            $table->tinyInteger('relationship', false, true)->nullable();
            $table->string('page_name',100)->nullable();
            $table->integer('parent_id', false, true)->length(11)->nullable();
            $table->string('title',150)->nullable();
            $table->text('keywords')->nullable();
            $table->text('description')->nullable();
            $table->string('layout',50)->nullable();
            $table->integer('menu_id', false, true)->length(11)->nullable();
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
        Schema::drop('pages');
    }
}
