<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnBusRouteId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign('daily_timetables_bus_route_id_foreign');
        });
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign('daily_timetables_operation_day_id_foreign');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('operation_day_id');
            $table->dropColumn('bus_route_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedInteger('bus_route_id');
            $table->unsignedInteger('operation_day_id');

            $table->foreign('operation_day_id')->references('id')->on('operation_days')->onDelete('cascade');
            $table->foreign('bus_route_id')->references('id')->on('bus_route')->onDelete('cascade');
        });
    }
}
