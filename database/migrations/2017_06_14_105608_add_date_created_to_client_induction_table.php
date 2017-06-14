<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateCreatedToClientInductionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_inductions', function($table) {
            $table->bigInteger('date_created')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
	
    public function down()
    {
         Schema::table('client_inductions', function($table) {
            $table->dropColumn('date_created');
        });
    }
}
