<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_histories', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('vehicle_id')->index()->nullable();
            $table->unsignedInteger('status')->index()->nullable();
            $table->string('comment')->index()->nullable();
            $table->bigInteger('action_date')->nullable();
			$table->unsignedInteger('user_id')->nullable();
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
        Schema::dropIfExists('vehicle_histories');
    }
}
