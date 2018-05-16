<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTicketPricesDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_price_default', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('start_location');
            $table->unsignedInteger('last_location');
            $table->unsignedInteger('bus_class_id');
            $table->integer('price');

            $table->foreign('start_location')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('last_location')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('bus_class_id')->references('id')->on('bus_classes')->onDelete('cascade');

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
        Schema::dropIfExists('ticket_price_default');
    }
}
