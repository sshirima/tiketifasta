<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableApprovalRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('stage');
            $table->enum('type',['BUS_ROUTE_ASSIGN','SCHEDULE_CANCEL','SCHEDULE_REASSIGNMENT']);
            $table->enum('status',['PENDING','APPROVED','SUSPENDED','REJECTED']);
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
        Schema::dropIfExists('approval_requests');
    }
}
