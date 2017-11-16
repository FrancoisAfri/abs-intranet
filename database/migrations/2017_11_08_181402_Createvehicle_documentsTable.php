<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatevehicleDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_documets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
             $table->string('description')->nullable();
             $table->string('document')->nullable();
             $table->bigInteger('upload_date')->nullable();
             $table->integer('user_name')->nullable();
             $table->integer('status')->nullable();
             $table->integer('vehicleID')->nullable()->unsigned()->index();
             $table->integer('default_documrnt')->nullable();
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
        Schema::dropIfExists('vehicle_documets');
    }
}
