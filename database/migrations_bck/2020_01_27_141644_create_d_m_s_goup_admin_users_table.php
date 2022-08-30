<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDMSGoupAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_m_s_goup_admin_users', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('hr_id')->nullable();
			$table->integer('division_level_5')->nullable();
			$table->integer('division_level_4')->nullable();
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
        Schema::dropIfExists('d_m_s_goup_admin_users');
    }
}
