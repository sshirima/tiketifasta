<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Schedule;
use App\Models\Trip;

class AddSchedulingDirection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

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
    }
}
