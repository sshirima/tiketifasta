<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullableBusesColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->unsignedInteger('route_id')->nullable()->change();
            $table->date('operation_start')->nullable()->change();
            $table->date('operation_end')->nullable()->change();
            $table->dateTime('route_starttime')->nullable()->change();
            $table->dateTime('route_endtime')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropForeign('buses_route_id_foreign');
        });

        Schema::table('buses', function (Blueprint $table) {

            $table->unsignedInteger('route_id')->nullable(false)->change();
            $table->date('operation_start')->nullable(false)->change();
            $table->date('operation_end')->nullable(false)->change();
            $table->dateTime('route_starttime')->nullable(false)->change();
            $table->dateTime('route_endtime')->nullable(false)->change();

            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
        });
    }
}
