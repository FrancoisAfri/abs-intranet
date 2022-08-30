<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumnToJobcardmaintainceTabl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
       Schema::table('jobcard_maintanance', function (Blueprint $table) {
           $table->Integer('step_no')->nullable();
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
            $table->dropColumn('step_no');
        });
    }
}

