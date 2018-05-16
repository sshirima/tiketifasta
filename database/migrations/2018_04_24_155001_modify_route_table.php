<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyRouteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropColumn('end_location');
        });
        Schema::table('routes', function (Blueprint $table) {

            $table->unsignedInteger('end_location')->after('start_location');
            $table->time('start_time')->after('end_location');
            $table->time('end_time')->after('start_time');
            $table->unsignedTinyInteger('travelling_days')->after('start_time')->default(1);

            $table->foreign('end_location')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropForeign('routes_end_location_foreign');
        });

        Schema::table('routes', function (Blueprint $table) {
            $table->dropColumn('end_location');
        });

        Schema::table('routes', function (Blueprint $table) {
            $table->string('end_location');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
            $table->dropColumn('travelling_days');
        });
    }
}
