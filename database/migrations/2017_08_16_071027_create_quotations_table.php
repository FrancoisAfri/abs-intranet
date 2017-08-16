<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned()->index()->nullable();
            $table->integer('client_id')->unsigned()->index()->nullable();
            $table->integer('division_id')->unsigned()->index()->nullable();
            $table->smallInteger('division_level')->nullable();
            $table->integer('hr_person_id')->unsigned()->index()->nullable();
            $table->integer('approval_person_id')->unsigned()->index()->nullable();
            $table->smallInteger('status')->nullable();
            $table->bigInteger('send_date')->nullable();
            $table->bigInteger('approval_date')->nullable();
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
        Schema::dropIfExists('quotations');
    }
}
