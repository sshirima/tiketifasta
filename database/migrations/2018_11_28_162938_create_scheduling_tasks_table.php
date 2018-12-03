<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulingTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('task_uid')->unique();
            $table->string('task_name');
            $table->string('task_description')->nullable();
            $table->string('run_interval')->nullable();
            $table->enum('interval_unit',['minute','hour','day','week','month','year'])->nullable();
            $table->enum('last_run_status',['failed','completed','skipped','never'])->default('never')->nullable();
            $table->timestamp('last_run')->nullable();
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
        Schema::dropIfExists('scheduled_tasks');
    }
}
