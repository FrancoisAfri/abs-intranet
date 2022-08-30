<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatepermitsLicenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permits_licence', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('Supplier')->nullable()->index();
            $table->unsignedInteger('permit_licence')->nullable();
            $table->bigInteger('date_issued')->nullable();
            $table->bigInteger('exp_date')->nullable();
            $table->integer('status')->nullable();
            $table->string('captured_by')->nullable()->index();
            $table->bigInteger('date_captured')->nullable();
            $table->string('document')->nullable()->index();
            $table->unsignedInteger('vehicleID')->nullable()->index();
            $table->integer('default_documrnt')->nullable();
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
        Schema::dropIfExists('permits_licence');
    }
}
