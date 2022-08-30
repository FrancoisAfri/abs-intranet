<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatejobcardsConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
      Schema::create('jobcard_config', function (Blueprint $table) {

            $table->increments('id');
            $table->smallInteger('use_procurement')->nullable();
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

        Schema::dropIfExists('jobcard_config');

    }

}

