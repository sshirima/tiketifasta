<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedInteger('sub_route_id')->after('schedule_id');

            $table->foreign('sub_route_id')->references('id')->on('subroutes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_sub_route_id_foreign');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('sub_route_id');
        });
    }
}
