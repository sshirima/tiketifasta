<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSubRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subroutes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('source');
            $table->unsignedInteger('destination');
            $table->time('depart_time');
            $table->time('arrival_time');
            $table->unsignedTinyInteger('travelling_days')->default(1);
            $table->unsignedInteger('sub_route_id');
            $table->timestamps();

            $table->foreign('source')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('destination')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('sub_route_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subroutes');
    }
}
