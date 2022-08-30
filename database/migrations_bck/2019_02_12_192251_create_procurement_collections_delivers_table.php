<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcurementCollectionsDeliversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurement_collections_delivers', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('procurement_id')->nullable();
			$table->integer('employee_id')->nullable();
			$table->integer('status')->nullable();
			$table->string('delivery_slip')->nullable();
			$table->string('delivery_number')->nullable();
			$table->bigInteger('date_delivered')->nullable();
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
        Schema::dropIfExists('procurement_collections_delivers');
    }
}
