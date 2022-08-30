<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepartmetsColumnsToContactCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_companies', function (Blueprint $table) {
            // $table->bigInteger('division_level_1')->index()->unsigned()->nullable();
            // $table->bigInteger('division_level_2')->index()->unsigned()->nullable();
            // $table->bigInteger('division_level_3')->index()->unsigned()->nullable();
            // $table->bigInteger('division_level_4')->index()->unsigned()->nullable();
            // $table->bigInteger('division_level_5')->index()->unsigned()->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_companies', function (Blueprint $table) {
            // $table->dropColumn('division_level_1');
            // $table->dropColumn('division_level_2');
            // $table->dropColumn('division_level_3');
            // $table->dropColumn('division_level_4');
            // $table->dropColumn('division_level_5');

        });
    }
}
