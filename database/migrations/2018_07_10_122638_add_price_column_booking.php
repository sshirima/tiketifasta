<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Booking;

class AddPriceColumnBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Booking::TABLE, function (Blueprint $table) {
            $table->integer(Booking::COLUMN_PRICE)->after(Booking::COLUMN_PHONE_NUMBER);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(Booking::TABLE, function (Blueprint $table) {
            $table->dropColumn(Booking::COLUMN_PRICE);
        });
    }
}
