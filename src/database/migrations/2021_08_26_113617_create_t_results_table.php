<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_results', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('task_id');
            $table->unsignedInteger('position_id');
            $table->integer('status')->default(1);
            $table->json('answers')->nullable()->default(null);
            $table->float('result')->default(0);
            $table->integer('penalty')->default(0);
            $table->unsignedInteger('time')->default(0);
            $table->text('comment')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('t_tasks')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('position_id')->references('id')->on('t_positions')
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
        Schema::dropIfExists('t_results');
    }
}
