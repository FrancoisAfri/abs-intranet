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
			$table->integer('job_card_id')->unsigned()->index()->nullable();
            $table->integer('status')->unsigned()->index()->nullable();
            $table->string('comment')->unsigned()->index()->nullable();
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
