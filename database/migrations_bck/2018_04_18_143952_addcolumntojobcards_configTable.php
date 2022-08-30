<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumntojobcardsConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
     Schema::table('jobcard_config', function (Blueprint $table) {
           $table->smallInteger('mechanic_sms')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobcard_config', function (Blueprint $table) {
            $table->dropColumn('mechanic_sms');
        });
    }
}