<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateprocessflowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
      Schema::create('jobcard_process_flow', function (Blueprint $table) {

            $table->increments('id');
            $table->smallInteger('use_procurement')->nullable();
            $table->Integer('step_number')->nullable();
            $table->string('step_name')->nullable();
            $table->smallInteger('job_title')->nullable();
            $table->smallInteger('status')->nullable();
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

        Schema::dropIfExists('jobcard_process_flow');

    }

}
