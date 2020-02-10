<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDMSCompanyAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_m_s_company_accesses', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('hr_id')->nullable();
			$table->integer('division_level_1')->nullable();
			$table->integer('division_level_2')->nullable();
			$table->integer('division_level_3')->nullable();
			$table->integer('division_level_4')->nullable();
			$table->integer('division_level_5')->nullable();
			$table->integer('folder_id')->nullable();
			$table->integer('file_id')->nullable();
			$table->integer('status')->nullable();
			$table->integer('admin_id')->nullable();
			$table->bigInteger('expiry_date')->nullable();
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
        Schema::dropIfExists('d_m_s_company_accesses');
    }
}
