<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnToJobcardmaintainceTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
       Schema::table('jobcard_maintanance', function (Blueprint $table) {
           $table->bigInteger('completion_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobcard_maintanance', function (Blueprint $table) {
            $table->dropColumn('completion_date');
        });
    }
}

