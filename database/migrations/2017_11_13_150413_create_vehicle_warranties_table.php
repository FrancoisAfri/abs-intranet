<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleWarrantiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_warranties', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_provider')->nullable()->unsigned()->index();
            $table->string('contact_person')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('address')->nullable();
            $table->string('policy_no')->nullable();
            $table->bigInteger('inception_date')->nullable();
            $table->bigInteger('exp_date')->nullable();
            $table->smallInteger('warranty_period')->nullable();
            $table->integer('kilometers')->nullable();
            $table->string('type')->nullable();
            $table->double('warranty_amount')->nullable();
            $table->string('description')->nullable();
            $table->string('notes')->nullable();
            $table->smallinteger('status')->nullable();
            $table->string('document')->nullable();
            $table->string('name')->nullable();
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
        Schema::dropIfExists('vehicle_warranties');
    }
}
