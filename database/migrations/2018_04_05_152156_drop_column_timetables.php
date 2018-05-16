<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnTimetables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timetables', function (Blueprint $table) {
            $table->dropForeign('timetables_bus_id_foreign');
        });

        Schema::table('timetables', function (Blueprint $table) {

            $table->dropColumn('bus_id');
        });

        Schema::table('timetables', function (Blueprint $table) {

            $table->unsignedInteger('busroute_id')->after('id');

            $table->foreign('busroute_id')->references('id')->on('bus_route')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('timetables', function (Blueprint $table) {
            $table->dropForeign('timetables_busroute_id_foreign');
        });

        Schema::table('timetables', function (Blueprint $table) {

            $table->dropColumn('busroute_id');
        });

        Schema::table('timetables', function (Blueprint $table) {

            $table->unsignedInteger('bus_id')->after('id');

            $table->foreign('bus_id')->references('id')->on('buses')->onDelete('cascade');
        });
    }
}
