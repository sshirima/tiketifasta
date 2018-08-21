<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Schedule;
use App\Models\Bus;
use App\Models\Trip;

class UpdateSchedulingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Schedule::TABLE, function (Blueprint $table) {
            $table->dropForeign('schedules_bus_route_id_foreign');
        });

        Schema::table(Schedule::TABLE, function (Blueprint $table) {
            $table->dropColumn('bus_route_id');
        });

        Schema::table(Schedule::TABLE, function (Blueprint $table) {
            $table->unsignedInteger(Schedule::COLUMN_BUS_ID)->after('id')->nullable();
            $table->foreign(Schedule::COLUMN_BUS_ID)->references(Bus::COLUMN_ID)->on(Bus::TABLE)->onDelete('cascade');
        });

        Schema::table(Schedule::TABLE, function (Blueprint $table) {
            $table->enum(Schedule::COLUMN_DIRECTION,Trip::TRIP_DIRECTIONS)->after(Schedule::COLUMN_DAY_ID)->default('GO');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(Schedule::TABLE, function (Blueprint $table) {
            $table->dropColumn(Schedule::COLUMN_DIRECTION);
        });
        Schema::table(Schedule::TABLE, function (Blueprint $table) {
            $table->dropForeign('schedules_bus_id_foreign');
        });

        Schema::table(Schedule::TABLE, function (Blueprint $table) {
            $table->dropColumn('bus_id');
        });

        Schema::table(Schedule::TABLE, function (Blueprint $table) {
            $table->unsignedInteger('bus_route_id')->after('id');
            $table->foreign('bus_route_id')->references('id')->on('bus_route')->onDelete('cascade');
        });
    }
}
