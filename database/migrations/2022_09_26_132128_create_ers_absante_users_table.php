<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErsAbsanteUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ers_absent_users', function($table) {
            $table->increments('id');
            $table->unsignedBigInteger('hr_id')->nullable();
            $table->smallInteger('is_applied')->nullable()->default(0);
            $table->bigInteger('date')->nullable();
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
        Schema::dropIfExists('ers_absent_users');
    }
}
