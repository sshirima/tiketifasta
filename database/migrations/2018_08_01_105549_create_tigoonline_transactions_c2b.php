<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\TigoOnlineC2B;

class CreateTigoonlineTransactionsC2b extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tigoonline_c2b', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference')->unique();
            $table->string('phone_number');
            $table->integer('amount');
            $table->string('currency',5)->default('TZS');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->nullable();
            $table->string('access_token')->nullable();
            $table->string('auth_code')->nullable();
            $table->integer('tax')->nullable();
            $table->integer('fee')  ->nullable();
            $table->enum('status',[TigoOnlineC2B::STATUS_SUCCESS,TigoOnlineC2B::STATUS_FAIL,TigoOnlineC2B::STATUS_UNAUTHORIZED ])->nullable();
            $table->string('external_ref_id')->nullable();
            $table->string('mfs_id')->nullable();
            $table->string('error_code')->nullable();
            $table->dateTime('authorized_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tigoonline_c2b');
    }
}
