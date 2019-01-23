<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\BusImage;
use App\Models\Bus;

class CreaateTableBusImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(BusImage::TABLE, function (Blueprint $table) {
            $table->increments(BusImage::COL_ID);
            $table->string(BusImage::COL_NAME);
            $table->unsignedInteger(BusImage::COL_BUS_ID);
            $table->timestamps();

            $table->foreign(BusImage::COL_BUS_ID)->references(Bus::COLUMN_ID)->on(Bus::TABLE)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(BusImage::TABLE);
    }
}
