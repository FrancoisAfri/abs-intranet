<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFiedlsToUnresolvedTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unresolved_tickets_settings', function (Blueprint $table) {
            $table->integer('critical_oficehrs')->nullable()->unsigned()->index();
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
            $table->dropColumn('critical_oficehrs');
        });
    }
}
