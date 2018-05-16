<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSeatSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_seat', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('seat_id');
            $table->unsignedInteger('schedule_id');
            $table->enum('status',['Available','Unavailable','Booked','Suspended'])->default('Available');

            $table->foreign('seat_id')->references('id')->on('seats')->onDelete('cascade');
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_seat');
    }
}
