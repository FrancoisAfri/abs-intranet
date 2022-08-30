<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToComplaintsComplimentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaints_compliments', function (Blueprint $table) {
            $table->unsignedInteger('manager_id')->index()->nullable();
            $table->string('closing_comment')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('complaints_compliments', function (Blueprint $table) {
            $table->dropColumn('closing_comment');
            $table->dropColumn('manager_id');
        });
    }
}
