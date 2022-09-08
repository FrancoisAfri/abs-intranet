<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetTransfaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_transfer', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->smallInteger('transfer_to')->default(1); // default user(1) , store (2)
            $table->unsignedInteger('store_id')->nullable();
            $table->unsignedInteger('asset_id')->nullable();
            $table->string('picture_before')->nullable();
            $table->string('picture_after')->nullable();
            $table->string('document')->nullable();
            $table->date('transaction_date')->nullable();
            $table->date('transfer_date')->nullable();
            $table->string('asset_status')->index()->nullable()->default('Un Allocated');
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
