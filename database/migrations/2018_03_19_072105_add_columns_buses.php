<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsBuses extends Migration
{
    public function up()
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->date('operation_start')->after('route_id')->nullable();
            $table->date('operation_end')->after('operation_start')->nullable();
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
            $table->dropColumn('operation_start');
            $table->dropColumn('operation_end');
        });

    }
}
