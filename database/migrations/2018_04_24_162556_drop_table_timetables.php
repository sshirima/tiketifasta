<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTableTimetables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign('tickets_timetable_id_foreign');
        });
        Schema::table('ticketprices', function (Blueprint $table) {
            $table->dropForeign('ticketprices_timetable_id_foreign');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_timetable_id_foreign');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('timetable_id');
        });
        Schema::table('ticketprices', function (Blueprint $table) {
            $table->dropColumn('timetable_id');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('timetable_id');
        });
        Schema::table('daily_timetables', function (Blueprint $table) {
            $table->dropForeign('daily_timetables_timetable_id_foreign');
        });
        Schema::table('daily_timetables', function (Blueprint $table) {
            $table->dropColumn('timetable_id');
        });

        Schema::drop('timetables');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('busroute_id');
            $table->unsignedInteger('location_id');
            $table->time('arrival_time');
            $table->time('depart_time');

            $table->foreign('busroute_id')->references('id')->on('bus_route')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedInteger('timetable_id')->after('traveller_name');
            $table->foreign('timetable_id')->references('id')->on('timetables')->onDelete('cascade');
        });
        Schema::table('ticketprices', function (Blueprint $table) {
            $table->unsignedInteger('timetable_id')->after('id');
            $table->foreign('timetable_id')->references('id')->on('timetables')->onDelete('cascade');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedInteger('timetable_id')->after('email');
            $table->foreign('timetable_id')->references('id')->on('timetables')->onDelete('cascade');
        });
    }
}
