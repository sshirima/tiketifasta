<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Trip;

class AddColumnPriceTrip extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Trip::TABLE, function (Blueprint $table) {
            $table->unsignedInteger(Trip::COLUMN_PRICE)->after(Trip::COLUMN_TRAVELLING_DAYS)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(Trip::TABLE, function (Blueprint $table) {
            $table->dropColumn(Trip::COLUMN_PRICE);
        });
    }
}
