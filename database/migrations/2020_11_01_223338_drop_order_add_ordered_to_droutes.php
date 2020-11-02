<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropOrderAddOrderedToDroutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('droutes', function ($table) {
            $table->dropColumn('order');
            $table->integer('ordered', false, true)->length(11)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->integer('order', false, true)->length(11)->nullable();
    }
}
