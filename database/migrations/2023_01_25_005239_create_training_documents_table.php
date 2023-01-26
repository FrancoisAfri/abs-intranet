<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_documents', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('document')->nullable();
            $table->integer('status')->nullable();
            $table->unsignedBigInteger('date_added')->index()->nullable();
			$table->integer('division_level_1')->unsigned()->index()->nullable();
            $table->integer('division_level_2')->unsigned()->index()->nullable();
            $table->integer('division_level_3')->unsigned()->index()->nullable();
            $table->integer('division_level_4')->unsigned()->index()->nullable();
            $table->integer('division_level_5')->unsigned()->index()->nullable();
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
        Schema::dropIfExists('training_documents');
    }
}
