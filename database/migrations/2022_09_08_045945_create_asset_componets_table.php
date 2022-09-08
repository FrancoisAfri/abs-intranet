<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetComponetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_components', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->integer('size')->nullable()->default(0);
            $table->unsignedInteger('asset_id')->nullable();
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
        Schema::drop('asset_components');
    }
}
