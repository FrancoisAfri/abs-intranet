<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicensesDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_license_dates', function ($table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->date('purchase_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->date('renewal_date')->nullable();
            $table->unsignedInteger('license_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->smallInteger('status')->default(1)->nullable();
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
        //
    }
}
