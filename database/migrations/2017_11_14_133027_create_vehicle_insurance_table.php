<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleInsuranceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_insurance', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('registration')->nullable()->unsigned()->index();
            $table->integer('service_provider')->nullable()->unsigned()->index();
            $table->string('contact_person')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('address')->nullable();
            $table->string('policy_no')->nullable();
            $table->bigInteger('inception_date')->nullable();
            $table->string('type')->nullable();
            $table->double('value_coverd')->nullable();
            $table->smallInteger('policy_type')->nullable();
            $table->double('premium_amount')->nullable();
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
        Schema::dropIfExists('vehicle_insurance');
    }
}
