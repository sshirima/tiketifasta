<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentIdB2cModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mpesa_b2c', function (Blueprint $table) {
            $table->unsignedInteger('merchant_payment_id')->nullable()->after('charges_paid_funds');

            $table->foreign('merchant_payment_id')->references('id')->on('merchant_payments');
        });

        Schema::table('tigo_b2c', function (Blueprint $table) {
            $table->unsignedInteger('merchant_payment_id')->nullable()->after('language');

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
        Schema::table('mpesa_b2c', function (Blueprint $table) {
            $table->dropForeign('mpesa_b2c_merchant_payment_id_foreign');
        });

        Schema::table('tigo_b2c', function (Blueprint $table) {
            $table->dropForeign('tigo_b2c_merchant_payment_id_foreign');
        });

        Schema::table('mpesa_b2c', function (Blueprint $table) {
            $table->dropColumn('merchant_payment_id');
        });

        Schema::table('tigo_b2c', function (Blueprint $table) {
            $table->dropColumn('merchant_payment_id');
        });
    }
}
