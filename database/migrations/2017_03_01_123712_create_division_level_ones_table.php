<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionLevelOnesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_level_ones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->smallInteger('active')->nullable();
            $table->integer('manager_id')->unsigned()->index()->nullable();
            $table->integer('parent_id')->unsigned()->index()->nullable();
            $table->integer('division_level_id')->unsigned()->index()->nullable();
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
        Schema::dropIfExists('division_level_ones');
    }
}
