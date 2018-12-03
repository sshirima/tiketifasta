<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMerchantPayId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_payments', function (Blueprint $table) {

            $table->unsignedInteger('merchant_payment_id')->nullable()->after('phone_number');

            $table->foreign('merchant_payment_id')->references('id')->on('merchant_payments');
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
            $table->dropForeign('booking_payments_merchant_payment_id_foreign');
        });

        Schema::table('booking_payments', function (Blueprint $table) {
            $table->dropColumn('merchant_payment_id');
        });
    }
}
