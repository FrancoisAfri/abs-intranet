<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_generalcosts', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('date')->nullable();
            $table->string('document_number')->nullable();
            $table->string('supplier_name')->nullable();
            $table->integer('cost_type')->nullable();
            $table->double('cost')->nullable();
            $table->integer('litres')->nullable();
            $table->string('description')->nullable();
            $table->integer('person_esponsible')->nullable()->unsigned()->index();
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
        Schema::dropIfExists('vehicle_generalcosts');
    }
}
