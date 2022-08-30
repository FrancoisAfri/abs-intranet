<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_serviceDetails', function (Blueprint $table) {
            $table->increments('id');
             $table->bigInteger('date_serviced')->nullable();
             $table->string('garage')->nullable();
             $table->string('invoice_number')->nullable();
             $table->double('total_cost')->nullable();
             $table->bigInteger('nxt_service_date')->nullable();
             $table->integer('nxt_service_km')->nullable();
             $table->string('description')->nullable();
             $table->string('document')->nullable();
             $table->string('document1')->nullable();
             $table->string('document2')->nullable();
             $table->integer('vehicleID')->nullable()->unsigned()->index();
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
        Schema::dropIfExists('vehicle_serviceDetails');
    }
}
