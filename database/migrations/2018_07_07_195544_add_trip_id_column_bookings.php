<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Booking;

class AddTripIdColumnBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Booking::TABLE, function (Blueprint $table) {
            $table->unsignedInteger(Booking::COLUMN_TRIP_ID)->after(Booking::COLUMN_SCHEDULE_ID)->nullable();
            $table->foreign(Booking::COLUMN_TRIP_ID)->references(\App\Models\Trip::COLUMN_ID)->on(\App\Models\Trip::TABLE)->onDelete('cascade');
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
            $table->dropForeign('bookings_trip_id_foreign');
        });

        Schema::table(Booking::TABLE, function (Blueprint $table) {
            $table->dropColumn(Booking::COLUMN_TRIP_ID);
        });
    }
}
