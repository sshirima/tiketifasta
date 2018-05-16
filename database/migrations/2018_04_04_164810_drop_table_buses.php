<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTableBuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropForeign('buses_route_id_foreign');
        });

        Schema::table('buses', function (Blueprint $table) {

            $table->dropColumn('route_id');
            $table->dropColumn('route_starttime');
            $table->dropColumn('route_endtime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->unsignedInteger('route_id')->nullable();
            $table->dateTime('route_starttime')->nullable();
            $table->dateTime('route_endtime')->nullable();

            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
        });
    }
}
