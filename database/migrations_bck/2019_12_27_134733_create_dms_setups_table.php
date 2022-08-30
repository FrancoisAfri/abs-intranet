<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmsSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dms_setups', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('use_remote_server')->nullable();
			$table->string('root_directory')->nullable();
			$table->string('default_image')->nullable();
			$table->string('use_remote_ftp_url')->nullable();
			$table->string('use_remote_ftp_username')->nullable();
			$table->string('use_remote_ftp_password')->nullable();
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
        Schema::dropIfExists('dms_setups');
    }
}
