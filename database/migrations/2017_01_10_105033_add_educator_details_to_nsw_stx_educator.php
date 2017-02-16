<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEducatorDetailsToNswStxEducator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('nsw_stx_educators', function($table) {
            $table->string('educator_id_number')->nullable();
			$table->string('educator_first_name')->nullable();
			$table->string('educator_surname')->nullable();
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
            $table->dropColumn('educator_id_number');
			$table->dropColumn('educator_first_name');
			$table->dropColumn('educator_surname');
        });
        
    }
}
