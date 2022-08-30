<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountNumberToContactCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
    */
	
    public function up()
    {
        Schema::table('contact_companies', function($table) {
            $table->string('account_number')->nullable();
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
            $table->dropColumn('account_number');
        });
    }
}