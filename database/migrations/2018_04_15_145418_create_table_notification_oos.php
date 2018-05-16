<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNotificationOos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_oos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('details');
            $table->unsignedInteger('daily_timetable_id');
            $table->timestamps();

            $table->foreign('daily_timetable_id')->references('id')->on('daily_timetables')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_oos');
    }
}
