<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsCommunicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts_communications', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('communication_type')->unsigned()->index()->nullable();
            $table->string('message')->nullable();
            $table->integer('vehicle_id')->nullable();
            $table->integer('status')->nullable();
            $table->integer('sent_by')->nullable();
            $table->bigInteger('communication_date')->nullable();
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
        Schema::dropIfExists('contacts_communications');
    }
}
