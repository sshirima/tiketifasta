<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsDestinations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn('arrival_time');
            $table->dropColumn('departure_time');
        });

        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->time('arrival_time')->nullable();
            $table->time('departure_time')->nullable();
        });
        Schema::table('destinations', function (Blueprint $table) {
            $table->enum('type',['START','INTERMEDIATE','END'])->nullable()->default('INTERMEDIATE');
        });
    }
}
