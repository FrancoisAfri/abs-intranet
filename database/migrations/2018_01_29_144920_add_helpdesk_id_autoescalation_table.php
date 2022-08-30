<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHelpdeskIdAutoescalationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auto_escalation_settings', function (Blueprint $table) {
            $table->unsignedInteger('helpdesk_id')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auto_escalation_settings', function (Blueprint $table) {
            $table->dropColumn('helpdesk_id');
        });
    }
}
