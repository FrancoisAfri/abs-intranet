<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTojobcardMaintanances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
       Schema::table('jobcard_maintanance', function (Blueprint $table) {
            $table->string('reject_reason')->nullable();
            $table->bigInteger('reject_timestamp')->nullable();
            $table->integer('rejector_id')->unsigned()->index()->nullable();
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
            $table->dropColumn('reject_reason');
            $table->dropColumn('reject_timestamp');
            $table->dropColumn('rejector_id');
        });
    }
}


