<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bus_id');
            $table->enum('weekday',['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']);

            $table->foreign('bus_id')->references('id')->on('buses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recurs');
    }
}
