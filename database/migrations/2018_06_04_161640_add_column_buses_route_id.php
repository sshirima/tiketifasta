<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Bus;
use App\Models\Route;
class AddColumnBusesRouteId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(Bus::TABLE, function (Blueprint $table) {
            $table->unsignedInteger(Bus::COLUMN_ROUTE_ID)->after(Bus::COLUMN_CONDUCTOR_NAME)->nullable();

            $table->foreign(Bus::COLUMN_ROUTE_ID)->references(Route::COLUMN_ID)->on(Route::TABLE)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(Bus::TABLE, function (Blueprint $table) {
            $table->dropForeign('bus_route_id_foreign');
        });
        Schema::table(Bus::TABLE, function (Blueprint $table) {
            $table->dropColumn(Bus::COLUMN_ROUTE_ID);
        });
    }
}
