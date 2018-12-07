<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\MerchantPayment;

class AddStatusColumnsMerchantsPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_payments', function (Blueprint $table) {
            $table->enum('transaction_status',MerchantPayment::TRANS_STATUES)
                ->after('transfer_status')
                ->default(MerchantPayment::TRANS_STATUS_PENDING)
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
        Schema::table('merchant_payments', function (Blueprint $table) {
            $table->dropColumn('transaction_status');
        });
    }
}
