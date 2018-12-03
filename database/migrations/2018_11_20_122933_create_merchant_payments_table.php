<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\MerchantPayment;

class CreateMerchantPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_ref');
            $table->string('net_amount')->nullable();
            $table->string('merchant_amount')->nullable();
            $table->string('commission_amount')->nullable();
            $table->enum('payment_mode',['tigopesa','mpesa','airtelmoney']);
            $table->unsignedInteger('payment_account_id');
            $table->enum('payment_stage',MerchantPayment::PAYMENT_STATUS)->default(MerchantPayment::PAYMENT_STATUS[0]);
            $table->boolean('transfer_status')->default(0);
            $table->timestamps();

            $table->foreign('payment_account_id')->references('id')->on('merchant_payment_accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_payments');
    }
}
