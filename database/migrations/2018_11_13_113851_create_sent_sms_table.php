<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSentSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_sms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sender');
            $table->string('receiver');
            $table->string('message');
            $table->enum('operator',['VODACOM','TIGO','AIRTEL','HALOTEL']);
            $table->boolean('is_sent')->default(0);
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
        Schema::dropIfExists('sent_sms');
    }
}
