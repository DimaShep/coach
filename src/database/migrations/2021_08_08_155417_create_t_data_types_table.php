<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTDataTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_data_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('name_plural');
            $table->string('slug');
            $table->string('model');
            $table->string('controller')->nullable()->default(null);
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_data_types');
    }
}
