<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('text')->nullable()->default(null);
            $table->string('type')->nullable()->default(null);
            $table->json('info')->nullable()->default(null);
            $table->json('questions')->nullable()->default(null);
            $table->string('time')->nullable()->default(null);
            $table->integer('penalty')->default(0);
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
        Schema::dropIfExists('t_tasks');
    }
}
