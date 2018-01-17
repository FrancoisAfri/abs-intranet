<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleReturnDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('vehicle_return_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type')->nullable();
            $table->string('description')->nullable();
            $table->string('document')->nullable();
            $table->bigInteger('upload_date')->nullable();
            $table->integer('user_name')->nullable();
            $table->integer('vehicleID')->nullable()->unsigned()->index();
            //$table->integer('bookingID')->nullable()->unsigned()->index();
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
         Schema::dropIfExists('vehicle_return_documents');
    }
}
