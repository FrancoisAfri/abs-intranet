<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleCollectImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_collect_image', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name')->nullable();
             $table->string('description')->nullable();
             $table->string('image')->nullable();
             $table->bigInteger('upload_date')->nullable();
             $table->integer('user_name')->nullable();
             $table->integer('status')->nullable();
             $table->integer('vehicle_maintanace')->nullable()->unsigned()->index();
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
        Schema::dropIfExists('vehicle_collect_image');
    }
}
