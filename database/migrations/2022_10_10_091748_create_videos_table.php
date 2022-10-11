<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('path')->nullable();
            $table->bigInteger('video_type')->nullable()->default(0);  // genegral = 0 and work =  1
            $table->integer('division_level_1')->unsigned()->index()->nullable();
            $table->integer('division_level_2')->unsigned()->index()->nullable();
            $table->integer('division_level_3')->unsigned()->index()->nullable();
            $table->integer('division_level_4')->unsigned()->index()->nullable();
            $table->integer('division_level_5')->unsigned()->index()->nullable();
            $table->smallInteger('status')->default(1);
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
        Schema::dropIfExists('videos');
    }
}
