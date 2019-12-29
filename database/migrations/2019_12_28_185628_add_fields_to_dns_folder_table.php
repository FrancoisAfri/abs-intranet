<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToDnsFolderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dms_folders', function (Blueprint $table) {
            $table->integer('division_5')->index()->unsigned()->nullable();
            $table->integer('division_4')->index()->unsigned()->nullable();
            $table->integer('division_3')->index()->unsigned()->nullable();
            $table->integer('division_2')->index()->unsigned()->nullable();
            $table->integer('division_1')->index()->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dms_folders', function (Blueprint $table) {
            $table->dropColumn('division_5');
            $table->dropColumn('division_4');
            $table->dropColumn('division_3');
            $table->dropColumn('division_2');
            $table->dropColumn('division_1');
        });
    }
}