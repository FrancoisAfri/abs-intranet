<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFleetCommunicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleet_communications', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('communication_type')->unsigned()->index()->nullable();
            $table->string('message')->nullable();
            $table->integer('vehicle_id')->nullable();
            $table->integer('status')->nullable();
            $table->integer('sent_by')->nullable();
            $table->bigInteger('communication_date')->nullable();
			$table->string('time_sent')->unsigned()->index()->nullable();
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
       Schema::dropIfExists('vehicles_communications');
    }
}
