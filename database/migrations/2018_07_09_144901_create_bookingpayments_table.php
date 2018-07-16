<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use  App\Models\Booking;
use App\Models\BookingPayment;

class CreateBookingpaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(BookingPayment::TABLE, function (Blueprint $table) {
            $table->increments(BookingPayment::COLUMN_ID);
            $table->uuid(BookingPayment::COLUMN_PAYMENT_REF);
            $table->integer(BookingPayment::COLUMN_AMOUNT);
            $table->unsignedInteger(BookingPayment::COLUMN_BOOKING_ID);
            $table->string(BookingPayment::COLUMN_PAYMENT_METHOD);
            $table->string(BookingPayment::COLUMN_PHONE_NUMBER);
            $table->timestamps();

           $table->foreign(BookingPayment::COLUMN_BOOKING_ID)->references(Booking::COLUMN_ID)->on(Booking::TABLE)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(BookingPayment::TABLE);
    }
}
