<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Booking;

class AddColumnPaymentRef extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Booking::TABLE, function (Blueprint $table) {
            $table->dropForeign('bookings_sub_route_id_foreign');
        });

        Schema::table(Booking::TABLE, function (Blueprint $table) {
            $table->dropColumn(Booking::COLUMN_SUB_ROUTE_ID);
        });

        Schema::table(Booking::TABLE, function (Blueprint $table) {
            $table->string(Booking::COLUMN_PAYMENT_REF)->after(Booking::COLUMN_PAYMENT)->nullable();
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
            $table->dropColumn(Booking::COLUMN_PAYMENT_REF);
        });
    }
}
