<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDMSGroupAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_m_s_group_accesses', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('admin_id')->nullable();
			$table->integer('hr_id')->nullable();
			$table->integer('file_id')->nullable();
			$table->integer('folder_id')->nullable();
			$table->integer('group_id')->nullable();
			$table->bigInteger('expiry_date')->nullable();
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
        Schema::dropIfExists('d_m_s_group_accesses');
    }
}
