<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('st_name');
            $table->enum('st_type',['boarding_point','dropping_point','bus_stand','bus_stop'])->default('bus_stop');
            $table->unsignedInteger('location_id');
            $table->unsignedInteger('merchant_id')->nullable();

            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stations');
    }
}
