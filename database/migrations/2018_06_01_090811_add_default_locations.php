<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Location;

class AddDefaultLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Arusha',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Kilimanjaro',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Tanga',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Morogoro',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Ruvuma',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Kagera',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Mwanza',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Mwanza',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Mara',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Shinyanga',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Tabora',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Kigoma',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Rukwa',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Mbeya',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Iringa',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Dodoma',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Singida',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Dar es Salaam',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Mtwara',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Pwani',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Lindi',));
        DB::table(Location::TABLE)->insert(array(Location::COLUMN_NAME => 'Manyara',));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
