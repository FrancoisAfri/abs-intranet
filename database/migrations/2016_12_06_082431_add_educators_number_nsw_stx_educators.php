<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEducatorsNumberNswStxEducators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('nsw_stx_educators', function($table) {
            $table->integer('educators_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('nsw_stx_educators', function($table) {
            $table->dropColumn('educators_number');
            
        });
    }
}
