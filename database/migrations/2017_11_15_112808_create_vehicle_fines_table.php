<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleFinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_fines', function (Blueprint $table) {
            $table->increments('id');
             $table->bigInteger('date_captured')->nullable();
             $table->integer('fine_type')->nullable();
             $table->string('fine_ref')->nullable();
             $table->bigInteger('date_of_fine')->nullable();
             $table->bigInteger('time_of_fine')->nullable();
             $table->double('amount')->nullable();
             $table->double('reduced')->nullable();
             $table->double('additional_fee')->nullable();
             $table->string('location')->nullable();
             $table->integer('speed')->nullable();
             $table->integer('zone_speed')->nullable();   
             $table->integer('driver')->nullable()->unsigned()->index();
             $table->string('magistrate_office')->nullable();
             $table->bigInteger('court_date')->nullable();
             $table->bigInteger('paid_date')->nullable();
             $table->double('amount_paid')->nullable();
             $table->string('description')->nullable();
             $table->integer('fine_status')->nullable(); 
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
        Schema::dropIfExists('vehicle_fines');
    }
}
