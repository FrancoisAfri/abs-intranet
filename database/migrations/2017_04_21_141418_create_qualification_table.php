<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQualificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qualification', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Institution')->nullable();
            $table->string('Qualification')->nullable();
            $table->bigInteger('Year Obtained')->nullable();
            $table->string('Qualification Type')->nullable();
            $table->string('Certificate')->nullable();
            $table->smallinteger('status')->nullable();
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
        Schema::dropIfExists('qualification');
    }
}
