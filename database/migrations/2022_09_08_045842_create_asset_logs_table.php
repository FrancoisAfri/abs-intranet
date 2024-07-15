<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_log', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->integer('user_id')->nullable();
            $table->string('action_type');
            $table->integer('asset_id');
            $table->integer('checkedout_to')->nullable();
            $table->integer('store_id')->nullable();
            $table->smallInteger('status')->index()->nullable()->default(1);
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
        Schema::drop('asset_log');
    }
}
