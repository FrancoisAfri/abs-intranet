<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_files', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('name')->index()->nullable();
            $table->string('description')->index()->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('asset_id');
            $table->string('document')->nullable();
            $table->date('date_added')->nullable();
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
        Schema::drop('asset_files');
    }
}
