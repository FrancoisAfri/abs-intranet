<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrUsersRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_users_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hr_id')->nullable();
            $table->integer('role_id')->nullable();       
            $table->smallInteger('status')->nullable();
            $table->bigInteger('date_allocated')->nullable();
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
        Schema::dropIfExists('hr_users_roles');
    }
}
