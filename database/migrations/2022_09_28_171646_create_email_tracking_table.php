<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_tracking', function($table) {
            $table->increments('id');
            $table->string('name')->index()->nullable();
            $table->unsignedBigInteger('hr_id')->nullable();
            $table->smallInteger('is_sent')->nullable()->default(0);
            $table->bigInteger('date_sent')->nullable();
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
        Schema::dropIfExists('manager_report');
    }
}
