<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTradingAsToContactCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_companies', function($table) {
            $table->string('trading_as')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('cp_cell_number')->nullable();
            $table->string('cp_home_number')->nullable();
            $table->string('fax_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('contact_companies', function($table) {
            $table->dropColumn('trading_as');
            $table->dropColumn('contact_person');
            $table->dropColumn('cp_cell_number');
            $table->dropColumn('cp_home_number');
            $table->dropColumn('fax_number');
        });
    }
}
