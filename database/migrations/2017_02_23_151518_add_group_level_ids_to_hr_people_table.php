<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupLevelIdsToHrPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_people', function($table) {
            $table->integer('group_level_one_id')->unsigned()->index()->nullable();
            $table->integer('group_level_two_id')->unsigned()->index()->nullable();
            $table->integer('group_level_three_id')->unsigned()->index()->nullable();
            $table->integer('group_level_four_id')->unsigned()->index()->nullable();
            $table->integer('group_level_five_id')->unsigned()->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_people', function($table) {
            $table->dropColumn('group_level_one_id');
            $table->dropColumn('group_level_two_id');
            $table->dropColumn('group_level_three_id');
            $table->dropColumn('group_level_four_id');
            $table->dropColumn('group_level_five_id');
        });
    }
}
