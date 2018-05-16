<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnAndFKeyReassignedBus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reassigned_buses', function (Blueprint $table) {
            $table->dropForeign('reassigned_buses_daily_timetable_id_foreign');
        });
        Schema::table('reassigned_buses', function (Blueprint $table) {
            $table->dropColumn('daily_timetable_id');
        });
        Schema::table('reassigned_buses', function (Blueprint $table) {
            $table->unsignedInteger('schedule_id')->after('id');
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
        Schema::table('reassigned_buses', function (Blueprint $table) {
            $table->dropForeign('reassigned_buses_schedule_id_foreign');
        });
        Schema::table('reassigned_buses', function (Blueprint $table) {
            $table->dropColumn('schedule_id');
        });
        Schema::table('reassigned_buses', function (Blueprint $table) {
            $table->unsignedInteger('daily_timetable_id')->after('id');
            $table->foreign('daily_timetable_id')->references('id')->on('schedules')->onDelete('cascade');
        });
    }
}
