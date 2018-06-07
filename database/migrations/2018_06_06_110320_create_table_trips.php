<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Trip;
use App\Models\Bus;
use App\Models\Location;

class CreateTableTrips extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Trip::TABLE, function (Blueprint $table) {
            $table->increments(Trip::COLUMN_ID);
            $table->unsignedInteger(Trip::COLUMN_SOURCE);
            $table->unsignedInteger(Trip::COLUMN_DESTINATION);
            $table->time(Trip::COLUMN_DEPART_TIME);
            $table->time(Trip::COLUMN_ARRIVAL_TIME);
            $table->unsignedInteger(Trip::COLUMN_BUS_ID);
            $table->unsignedTinyInteger(Trip::COLUMN_TRAVELLING_DAYS)->default(1);
            $table->boolean(Trip::COLUMN_STATUS)->default(0);
            $table->enum(Trip::COLUMN_DIRECTION,Trip::TRIP_DIRECTIONS);
            $table->timestamps();

            $table->foreign(Trip::COLUMN_SOURCE)->references(Location::COLUMN_ID)->on(Location::TABLE)->onDelete('cascade');
            $table->foreign(Trip::COLUMN_DESTINATION)->references(Location::COLUMN_ID)->on(Location::TABLE)->onDelete('cascade');
            $table->foreign(Trip::COLUMN_BUS_ID)->references(Bus::COLUMN_ID)->on(Bus::TABLE)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Trip::TABLE);
    }
}
