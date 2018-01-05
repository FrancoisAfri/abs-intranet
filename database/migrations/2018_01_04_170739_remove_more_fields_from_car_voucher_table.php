<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveMoreFieldsFromCarVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_vouchers', function($table) {
             $table->dropColumn('c_agency_addr3');
             $table->dropColumn('c_agency_addr_code');
             $table->dropColumn('c_Clnt_ref2_name');
             $table->dropColumn('c_clnt_ref2_Value');
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
