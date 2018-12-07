<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\BookingPayment;

class AddStatusColumnsBookingPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('PENDING','CANCELLED','CONFIRMED','EXPIRED','AUTHORIZED')");

        Schema::table('booking_payments', function (Blueprint $table) {
            $table->enum('transaction_status',BookingPayment::TRANS_STATUES)
                ->after('merchant_payment_id')
                ->default(BookingPayment::TRANS_STATUS_PENDING)
                ->nullable();
        });

        Schema::table('tigo_b2c', function (Blueprint $table) {
            $table->enum('transaction_status',BookingPayment::TRANS_STATUES)
                ->after('merchant_payment_id')
                ->default(BookingPayment::TRANS_STATUS_PENDING)
                ->nullable();
        });

        Schema::table('tigoonline_c2b', function (Blueprint $table) {
            $table->enum('transaction_status',BookingPayment::TRANS_STATUES)
                ->after('booking_payment_id')
                ->default(BookingPayment::TRANS_STATUS_PENDING)
                ->nullable();
        });

        Schema::table('mpesa_c2b', function (Blueprint $table) {
            $table->enum('transaction_status',BookingPayment::TRANS_STATUES)
                ->after('booking_payment_id')
                ->default(BookingPayment::TRANS_STATUS_PENDING)
                ->nullable();
        });

        Schema::table('mpesa_b2c', function (Blueprint $table) {
            $table->enum('transaction_status',BookingPayment::TRANS_STATUES)
                ->after('status')
                ->default(BookingPayment::TRANS_STATUS_PENDING)
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_payments', function (Blueprint $table) {
            $table->dropColumn('transaction_status');
        });
        Schema::table('tigo_b2c', function (Blueprint $table) {
            $table->dropColumn('transaction_status');
        });
        Schema::table('tigoonline_c2b', function (Blueprint $table) {
            $table->dropColumn('transaction_status');
        });
        Schema::table('mpesa_c2b', function (Blueprint $table) {
            $table->dropColumn('transaction_status');
        });
        Schema::table('mpesa_b2c', function (Blueprint $table) {
            $table->dropColumn('transaction_status');
        });
    }
}
