<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTicketprice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticketprices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('timetable_id');
            $table->enum('ticket_type',['Child','Adult'])->default('Adult');
            $table->integer('price')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->foreign('timetable_id')->references('id')->on('timetables')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticketprices');
    }
}
