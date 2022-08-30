<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldToCarVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_vouchers', function (Blueprint $table) {
            $table->string('c_bill_postal_address3')->nullable();
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
            $table->dropColumn('c_bill_postal_address3');
        });
    }
}
