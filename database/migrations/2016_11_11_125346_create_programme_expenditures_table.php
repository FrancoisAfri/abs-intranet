<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgrammeExpendituresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programme_expenditures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('programme_id')->unsigned()->index()->nullable();
            $table->double('amount')->nullable();
            $table->bigInteger('date_added')->nullable();
            $table->string('payee')->nullable();
            $table->integer('payee_id')->unsigned()->index()->nullable();
            $table->string('supporting_doc')->nullable();
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
        Schema::dropIfExists('programme_expenditures');
    }
}
