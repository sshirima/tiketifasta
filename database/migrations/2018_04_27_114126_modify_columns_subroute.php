<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyColumnsSubroute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subroutes', function (Blueprint $table) {
            $table->dropForeign('subroutes_sub_route_id_foreign');
        });
        Schema::table('subroutes', function (Blueprint $table) {
            $table->dropColumn('sub_route_id');
        });
        Schema::table('subroutes', function (Blueprint $table) {
            $table->unsignedInteger('bus_route_id')->after('travelling_days');
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
        Schema::table('subroutes', function (Blueprint $table) {
            $table->dropForeign('subroutes_bus_route_id_foreign');
        });

        Schema::table('subroutes', function (Blueprint $table) {
            $table->dropColumn('sub_route_id');
        });

        Schema::table('subroutes', function (Blueprint $table) {
            $table->unsignedInteger('sub_route_id')->after('travelling_days');
            $table->foreign('sub_route_id')->references('id')->on('bus_route')->onDelete('cascade');
        });
    }
}
