<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobcartPartstable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::create('jobcard_parts', function (Blueprint $table) {
            $table->increments('id');
            $table->String('name')->nullable();
            $table->String('description')->nullable();   
            $table->integer('no_of_parts_available')->nullable();
            $table->smallInteger('status')->nullable();
            $table->integer('category_id')->unsigned()->index()->nullable();
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

        Schema::dropIfExists('jobcard_parts');

    }

}