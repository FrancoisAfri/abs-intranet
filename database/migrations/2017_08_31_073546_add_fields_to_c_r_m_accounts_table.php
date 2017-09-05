<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToCRMAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_r_m_accounts', function($table) {
            $table->integer('quote_id')->unsigned()->index()->nullable();
            $table->string('account_number')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_r_m_accounts', function($table) {
            $table->dropColumn('quote_id');
        });
    }
}
