<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePolicyRefreshedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policy_refreshed', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('policy_id')->index()->nullable();
            $table->unsignedInteger('hr_id')->index()->nullable();
            $table->integer('status')->nullable();
            $table->unsignedBigInteger('date_refreshed')->index()->nullable();
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
        Schema::dropIfExists('policy_refreshed');
    }
}
