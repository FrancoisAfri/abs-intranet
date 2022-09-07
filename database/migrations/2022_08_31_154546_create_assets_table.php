<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('name')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('description')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('asset_tag')->nullable();
            $table->string('model_number')->nullable();
            $table->string('make_number')->nullable();
            $table->unsignedInteger('asset_type_id')->nullable();
            $table->unsignedInteger('license_type_id')->nullable();
            $table->string('picture')->nullable();
            $table->decimal('price', 9, 2)->default(0);
            $table->bigInteger('status')->index()->nullable()->default(1);
            $table->string('asset_status')->index()->nullable();
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
        Schema::drop('assets');
    }
}
