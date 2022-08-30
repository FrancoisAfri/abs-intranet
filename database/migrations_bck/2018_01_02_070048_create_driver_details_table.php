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
            $table->string('licence_code')->unsigned()->index()->nullable();
            $table->string('licence_number')->unsigned()->index()->nullable();
            $table->bigInteger('licence_expiry_date')->unsigned()->index()->nullable();
            $table->string('licence_document')->nullable();
            $table->smallInteger('prof_driving_permit')->nullable();
            $table->bigInteger('pdp_expiry_date')->nullable();
            $table->string('driver_id_key_tag')->nullable();
            $table->integer('hr_person_id')->unsigned()->index()->nullable();
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
