<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToJobcardMaintananceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobcard_maintanance', function ($table) {
            $table->string('mechanic_comment')->unsigned()->index()->nullable();
            $table->string('completion_comment')->unsigned()->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobcard_maintanance', function ($table) {
            $table->dropColumn('mechanic_comment');
            $table->dropColumn('completion_comment');
        });
    }
}
