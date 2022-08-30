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
            $table->unsignedInteger('jobcard_parts_id')->index()->nullable();
            $table->unsignedInteger('jobcard_card_id')->index()->nullable();
            $table->unsignedInteger('category_id')->index()->nullable();
            $table->unsignedInteger('created_by')->index()->nullable();
            $table->unsignedBigInteger('date_created')->index()->nullable();
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

