<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatenewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->bigInteger('expirydate')->nullable();
            $table->string('supporting_docs')->nullable();
            $table->string('summary', 5000)->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('user_id')->index()->nullable();
            $table->unsignedInteger('division_level_1')->index()->nullable();
            $table->unsignedInteger('division_level_2')->index()->nullable();
            $table->unsignedInteger('division_level_3')->index()->nullable();
            $table->unsignedInteger('division_level_4')->index()->nullable();
            $table->unsignedInteger('division_level_5')->index()->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('cms_news');
    }
}
