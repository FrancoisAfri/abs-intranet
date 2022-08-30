<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmsFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dms_files', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('folder_id')->nullable();
			$table->integer('size')->nullable();
			$table->integer('locked')->nullable();
			$table->string('file_name')->nullable();
			$table->string('path')->nullable();
			$table->integer('responsable_person')->nullable();
			$table->integer('status')->nullable();
			$table->integer('deleted')->nullable();
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
        Schema::dropIfExists('dms_files');
    }
}