<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMpesab2c extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpesa_b2c', function (Blueprint $table) {
            $table->increments('id');
            $table->string('amount');
            $table->string('command_id');
            $table->string('initiator');
            $table->string('recipient');
            $table->timestamp('transaction_date')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('conversation_id')->nullable();
            $table->string('og_conversation_id')->nullable();
            $table->string('mpesa_receipt')->nullable();
            $table->string('result_type')->nullable();
            $table->string('result_code')->nullable();
            $table->string('working_account_funds')->nullable();
            $table->string('utility_account_funds')->nullable();
            $table->string('charges_paid_funds')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mpesa_b2c');
    }
}
