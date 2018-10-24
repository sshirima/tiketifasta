<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTigob2c extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tigo_b2c', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_id')->unique();
            $table->string('txn_id')->nullable();
            $table->string('amount')->nullable();
            $table->string('msisdn1')->nullable();
            $table->string('type')->nullable();
            $table->string('pin')->nullable();
            $table->string('msisdn')->nullable();
            $table->string('txn_status')->nullable();
            $table->string('txn_message')->nullable();
            $table->string('language')->nullable();
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
        Schema::dropIfExists('tigo_b2c');
    }
}
