<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriverDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drver_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('licence_code')->index()->nullable();
            $table->string('licence_number')->index()->nullable();
            $table->unsignedBigInteger('licence_expiry_date')->index()->nullable();
            $table->string('licence_document')->nullable();
            $table->smallInteger('prof_driving_permit')->nullable();
            $table->bigInteger('pdp_expiry_date')->nullable();
            $table->string('driver_id_key_tag')->nullable();
            $table->unsignedInteger('hr_person_id')->index()->nullable();
            $table->smallInteger('status')->nullable(); 
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
        Schema::dropIfExists('drver_details');
    }
}
