<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTPositionTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_position_tasks', function (Blueprint $table) {
            $table->unsignedInteger('position_id');
            $table->unsignedInteger('task_id');
            $table->unsignedInteger('parent_id');
            $table->longText('data')->nullable()->default(null);

            $table->foreign('position_id')->references('id')->on('t_positions')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('task_id')->references('id')->on('t_tasks')
                ->onDelete('cascade')->onUpdate('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_position_tasks');
    }
}
