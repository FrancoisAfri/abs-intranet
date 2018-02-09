<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHelpdeskIdToUnresolvedTicketsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unresolved_tickets_settings', function (Blueprint $table) {
            $table->integer('helpdesk_id')->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unresolved_tickets_settings', function (Blueprint $table) {
            $table->dropColumn('helpdesk_id');
        });
    }
}
