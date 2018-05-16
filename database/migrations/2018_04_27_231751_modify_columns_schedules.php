<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyColumnsSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign('schedules_sub_route_id_foreign');
        });
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('sub_route_id');
        });
        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedInteger('bus_route_id')->after('id');
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
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign('schedules_bus_route_id_foreign');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('bus_route_id');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedInteger('sub_route_id')->after('id');
            $table->foreign('sub_route_id')->references('id')->on('bus_route')->onDelete('cascade');
        });
    }
}
