<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HelpDeskSetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('helpDeskSetup', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description')->nullable();
            $table->integer('maximum_priority')->nullable();
            $table->bigInteger('time_from')->nullable();
            $table->bigInteger('time_to')->nullable();  
            $table->smallInteger('notify_hr_email')->nullable();
            $table->smallInteger('notify_hr_sms_sms')->nullable();
            $table->smallInteger('notify_manager_email')->nullable();
            $table->smallInteger('notify_manager_sms')->nullable();
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
         Schema::dropIfExists('helpDeskSetup');
    }
}
