<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssestDepreciationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_depreciations', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('notes')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('asset_id')->nullable();
            $table->unsignedInteger('months')->nullable();
            $table->unsignedInteger('years')->nullable();
            $table->double('amount_monthly')->nullable();
            $table->double('initial_amount')->nullable();
            $table->double('balance_amount')->nullable();
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
        Schema::drop('asset_depreciations');
    }
}
