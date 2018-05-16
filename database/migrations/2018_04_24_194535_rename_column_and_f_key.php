<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnAndFKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_daily_timetable_id_foreign');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('daily_timetable_id');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedInteger('schedule_id')->after('email');
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
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('reassigned_buses_schedule_id_foreign');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('schedule_id');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedInteger('daily_timetable_id')->after('seat_id');
            $table->foreign('daily_timetable_id')->references('id')->on('schedules')->onDelete('cascade');
        });
    }
}
