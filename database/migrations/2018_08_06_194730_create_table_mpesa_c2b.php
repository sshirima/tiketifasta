<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMpesaC2b extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpesa_c2b', function (Blueprint $table) {

            $table->increments('id');
            $table->string('amount');
            $table->string('command_id')->nullable();
            $table->string('account_reference')->unique();
            $table->string('msisdn',20);
            $table->string('initiator')->nullable();
            $table->string('og_conversation_id')->nullable();
            $table->string('recipient')->nullable();
            $table->string('mpesa_receipt')->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('service_receipt')->nullable();
            $table->string('conversation_id')->nullable();

            $table->enum('stage',['0','1','2','3'])->default('3');
            $table->enum('service_status',['pending','confirmed','cancelled'])->default('pending');
            $table->unsignedInteger('booking_payment_id')->nullable();

            $table->timestamp('authorized_at')->nullable();

            $table->timestamps();

            $table->foreign('booking_payment_id')->references('id')->on('booking_payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mpesa_c2b');
    }
}
