<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedInteger('sub_route_id')->after('id');
            $table->unsignedInteger('day_id')->after('sub_route_id');

            $table->foreign('sub_route_id')->references('id')->on('subroutes')->onDelete('cascade');
            $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
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
            $table->dropForeign('schedules_day_id_foreign');
        });
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign('schedules_sub_route_id_foreign');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('sub_route_id');
            $table->dropColumn('day_id');
        });
    }
}
