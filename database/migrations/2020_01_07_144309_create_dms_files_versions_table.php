<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmsFilesVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('dms_files_versions', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('file_id')->nullable();
			$table->string('file_name')->nullable();
			$table->string('path')->nullable();
			$table->integer('version_number')->nullable();
			$table->integer('status')->nullable();
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
        Schema::dropIfExists('dms_files_versions');
    }
}
