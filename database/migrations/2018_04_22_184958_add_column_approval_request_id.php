<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnApprovalRequestId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reassigned_buses', function (Blueprint $table) {
            $table->unsignedInteger('approval_request_id')->after('reassigned_bus_id');

            $table->foreign('approval_request_id')->references('id')->on('approval_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reassigned_buses', function (Blueprint $table) {
            $table->dropColumn('approval_request_id');
        });
    }
}
