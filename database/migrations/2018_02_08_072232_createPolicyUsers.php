<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePolicyUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('policyUsers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('policy_id')->index()->nullable();
            $table->unsignedInteger('user_id')->index()->nullable();
            // $table->integer('read_understood')->nullable();
            // $table->integer('read_not_understood')->nullable();
            // $table->integer('read_not_sure')->nullable();
            $table->integer('status')->nullable();
            $table->bigInteger('date_read')->nullable();
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
        Schema::dropIfExists('policyUsers');
    }
}
