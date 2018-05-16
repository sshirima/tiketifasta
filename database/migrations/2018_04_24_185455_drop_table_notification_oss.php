<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTableNotificationOss extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('notification_oos');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('notification_oos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('details');
            $table->unsignedInteger('day_id');
            $table->timestamps();

            $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
        });
    }
}
