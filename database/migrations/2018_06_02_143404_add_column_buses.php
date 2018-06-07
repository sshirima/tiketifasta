<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Bus;

class AddColumnBuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Bus::TABLE, function (Blueprint $table) {
            $table->string(Bus::COLUMN_DRIVER_NAME)->after(Bus::COLUMN_OPERATION_END)->nullable();
            $table->string(Bus::COLUMN_CONDUCTOR_NAME)->after(Bus::COLUMN_DRIVER_NAME)->nullable();
            $table->enum(Bus::COLUMN_BUS_CONDITION,Bus::DEFAULT_CONDITIONS)->after(Bus::COLUMN_STATE)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(Bus::TABLE, function (Blueprint $table) {
            $table->dropColumn(Bus::COLUMN_DRIVER_NAME);
            $table->dropColumn(Bus::COLUMN_CONDUCTOR_NAME);
            $table->dropColumn(Bus::COLUMN_BUS_CONDITION);
        });
    }
}
