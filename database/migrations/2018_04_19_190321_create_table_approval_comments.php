<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableApprovalComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('approval_request_id');
            $table->unsignedTinyInteger('approval_stage');
            $table->text('content');
            $table->timestamps();

            $table->foreign('approval_request_id')->references('id')->on('approval_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approval_comments');
    }
}
