<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTableTicketPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticketprices', function (Blueprint $table) {
            $table->unsignedInteger('sub_route_id')->after('id');

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
        Schema::table('ticketprices', function (Blueprint $table) {
            $table->dropForeign('ticketprices_sub_route_id_foreign');
        });

        Schema::table('ticketprices', function (Blueprint $table) {
            $table->dropColumn('sub_route_id');
        });
    }
}
