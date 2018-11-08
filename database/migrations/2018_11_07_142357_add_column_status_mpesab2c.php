<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnStatusMpesab2c extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mpesa_b2c', function (Blueprint $table) {
            $table->string('init_code')->nullable()->after('result_desc');
            $table->string('init_desc')->nullable()->after('init_code');
            $table->string('confirm_code')->nullable()->after('init_desc');
            $table->string('confirm_desc')->nullable()->after('confirm_code');
            $table->enum('status',\App\Models\MpesaB2C::STATUS_LEVEL)->nullable()->after('confirm_desc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mpesa_b2c', function (Blueprint $table) {
            $table->dropColumn('init_code');
            $table->dropColumn('init_desc');
            $table->dropColumn('confirm_code');
            $table->dropColumn('confirm_desc');
            $table->dropColumn('status');
        });
    }
}
