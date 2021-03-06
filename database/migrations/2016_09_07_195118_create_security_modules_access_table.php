<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecurityModulesAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('security_modules_access', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('module_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('access_level')->nullable();
			$table->integer('active')->nullable();
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
        Schema::dropIfExists('security_modules_access');
    }
}
