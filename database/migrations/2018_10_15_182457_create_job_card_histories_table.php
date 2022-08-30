<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobCardHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_card_histories', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('job_card_id')->index()->nullable();
            $table->unsignedInteger('status')->index()->nullable();
            $table->string('comment')->index()->nullable();
            $table->bigInteger('action_date')->nullable();
			$table->integer('user_id')->nullable();
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
        Schema::dropIfExists('job_card_histories');
    }
}
