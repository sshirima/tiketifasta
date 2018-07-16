<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Bus;

class RemoveOparationDaysBuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn(Bus::COLUMN_OPERATION_START);
            $table->dropColumn(Bus::COLUMN_OPERATION_END);
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
            $table->date(Bus::COLUMN_OPERATION_START)->after('merchant_id');
            $table->date(Bus::COLUMN_OPERATION_END)->after(Bus::COLUMN_OPERATION_START);
        });
    }
}
