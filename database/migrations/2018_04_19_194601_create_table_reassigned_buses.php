<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReassignedBuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reassigned_buses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('daily_timetable_id');
            $table->unsignedInteger('reassigned_bus_id');
            $table->boolean('status')->default(0);
            $table->timestamps();

            $table->foreign('daily_timetable_id')->references('id')->on('daily_timetables')->onDelete('cascade');
            $table->foreign('reassigned_bus_id')->references('id')->on('buses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reassigned_buses');
    }
}
