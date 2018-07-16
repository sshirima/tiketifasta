<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOparationDaysMerchants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->date('contract_start')->after('name')->nullable();
            $table->date('contract_end')->after('contract_start')->nullable();
            $table->boolean('status')->after('contract_end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('contract_end');
            $table->dropColumn('contract_start');
        });
    }
}
