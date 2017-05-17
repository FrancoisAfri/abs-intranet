<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAllowNegativedays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::table('leave_configuration', function (Blueprint $table) {
             $table->integer('allow_sick_negative_days')->nullable();
             $table->integer('allow_annual_negative_days')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
    {
        Schema::table('leave_configuration', function($table) {
            $table->dropColumn('allow_sick_negative_days');
            $table->dropColumn('allow_annual_negative_days');
          });  
    }
}
