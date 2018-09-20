<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\TigoOnlineC2B;

class AddBookingPaymentIdTigoonlinec2b extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tigoonline_c2b', function (Blueprint $table) {
            $table->unsignedInteger(TigoOnlineC2B::COLUMN_BOOKING_PAYMENT_ID)->after(TigoOnlineC2B::COLUMN_ERROR_CODE)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tigoonline_c2b', function (Blueprint $table) {
            $table->dropColumn(TigoOnlineC2B::COLUMN_BOOKING_PAYMENT_ID);
        });
    }
}
