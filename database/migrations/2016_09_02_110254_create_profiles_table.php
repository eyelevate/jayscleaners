<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('profiles');
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id', false, true)->length(11)->nullable();
            $table->integer('user_id', false, true)->length(11)->nullable();
            $table->integer('profile_id', false, true)->length(11)->nullable();
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
        Schema::drop('profiles');
    }
}
