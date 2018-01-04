<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsToCarVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_vouchers', function (Blueprint $table) {
            $table->string('c_agency_addr3')->nullable();
            $table->string('c_agency_addr_code')->nullable();
            $table->string('c_clnt_ref2_name')->nullable();
            $table->string('c_clnt_ref2_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_vouchers', function (Blueprint $table) {
            $table->dropColumn('c_agency_addr3');
            $table->dropColumn('c_agency_addr_code');
            $table->dropColumn('c_clnt_ref2_name');
            $table->dropColumn('c_clnt_ref2_value');
        });
    }
}
