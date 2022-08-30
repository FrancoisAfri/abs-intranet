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
			$table->integer('division_level_2')->nullable();
            $table->integer('division_level_1')->nullable();
            $table->integer('hr_person_id')->nullable();
            $table->string('Institution')->nullable();
			$table->integer('Qualification_Type')->nullable();
            $table->string('Qualification')->nullable();
			$table->string('Major')->nullable();
            $table->bigInteger('yearObtained')->nullable();
			$table->smallinteger('Qualification_status')->nullable();
			$table->string('country')->nullable();
			$table->string('supporting_docs')->nullable();
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
