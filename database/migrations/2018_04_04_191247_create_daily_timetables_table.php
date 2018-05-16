<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyTimetablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_timetables', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('operation_day_id');
            $table->unsignedInteger('bus_route_id');

            $table->foreign('operation_day_id')->references('id')->on('operation_days')->onDelete('cascade');
            $table->foreign('bus_route_id')->references('id')->on('bus_route')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_timetables');
    }
}
