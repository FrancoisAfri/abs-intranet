<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToStockTble extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('stock', function (Blueprint $table) {
            $table->string('serial_number')->unsigned()->index()->nullable();
            $table->string('bar_code')->nullable()->index();;
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock', function (Blueprint $table) {
            $table->dropColumn('serial_number');
            $table->dropColumn('bar_code');
           
        });
    }
}
