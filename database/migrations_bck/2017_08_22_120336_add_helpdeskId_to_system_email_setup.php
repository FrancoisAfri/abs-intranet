<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHelpdeskIdToSystemEmailSetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_email_setup', function (Blueprint $table) {
           $table->integer('helpdesk_id')->unsigned()->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_email_setup', function (Blueprint $table) {
              $table->dropColumn('helpdesk_id');
        });
    }
}
