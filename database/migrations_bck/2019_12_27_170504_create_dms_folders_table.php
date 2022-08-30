<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmsFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dms_folders', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('parent_id')->nullable();
			$table->integer('size')->nullable();
			$table->integer('locked')->nullable();
			$table->integer('responsable_person')->nullable();
			$table->integer('status')->nullable();
			$table->integer('deleted')->nullable();
			$table->integer('inherit_rights')->nullable();
			$table->string('folder_name')->nullable();
			$table->string('path')->nullable();
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
        Schema::dropIfExists('dms_folders');
    }
}