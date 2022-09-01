<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licenses', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('serial');
            $table->date('purchase_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('purchase_cost', 8, 2)->nullable();
            $table->string('order_number');
            $table->text('notes');
            $table->integer('user_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->integer('depreciation_id');
            $table->unsignedBigInteger('licence_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
          Schema::drop('licenses');
    }
}
