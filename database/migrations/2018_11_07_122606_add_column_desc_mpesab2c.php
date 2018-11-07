<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDescMpesab2c extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mpesa_b2c', function (Blueprint $table) {
            $table->string('result_desc')->nullable()->after('result_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('mpesa_b2c', function (Blueprint $table) {
            $table->dropColumn('result_desc');
        });
    }
}
