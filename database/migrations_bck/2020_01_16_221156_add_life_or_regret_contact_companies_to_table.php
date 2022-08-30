<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLifeOrRegretContactCompaniesToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('contact_companies', function($table) {
            $table->string('live_regret')->nullable();
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
            $table->dropColumn('live_regret');
        });
    }
}