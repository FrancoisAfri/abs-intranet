<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrationModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registration_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('registration_id')->unsigned()->index()->nullable();
            $table->integer('module_id')->unsigned()->index()->nullable();
            $table->string('module_name')->nullable();
            $table->double('module_fee')->nullable();
            $table->double('result')->nullable();
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
        Schema::dropIfExists('registration_modules');
    }
}
