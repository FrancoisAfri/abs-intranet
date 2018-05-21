<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobcardOrderPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::create('jobcard__order_parts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('no_of_parts_used')->nullable();
            $table->integer('jobcard_parts_id')->unsigned()->index()->nullable();
            $table->integer('jobcard_card_id')->unsigned()->index()->nullable();
            $table->integer('category_id')->unsigned()->index()->nullable();
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->bigInteger('date_created')->unsigned()->index()->nullable();
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

        Schema::dropIfExists('jobcard__order_parts');

    }

}

