<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBusClasses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bus_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('class_name');
        });

        DB::table('bus_classes')->insert(array('class_name' => 'Normal'));
        DB::table('bus_classes')->insert(array('class_name' => 'Luxury'));
        DB::table('bus_classes')->insert(array('class_name' => 'Semi-Luxury '));
        DB::table('bus_classes')->insert(array('class_name' => 'Super-luxury'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bus_classes');
    }
}
