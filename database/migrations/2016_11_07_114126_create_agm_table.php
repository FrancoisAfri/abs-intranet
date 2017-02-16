<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agm', function (Blueprint $table) {
            $table->increments('id');
			$table->string('names')->nullable();
			$table->integer('representative')->nullable();
			$table->integer('type_attendees')->nullable();
			$table->string('email')->nullable();
			$table->string('office_number')->nullable();
			$table->string('cell_number')->nullable();
			$table->string('position')->nullable();
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
        Schema::dropIfExists('agm');
    }
}
