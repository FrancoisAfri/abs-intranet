<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToStockRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_stocks', function ($table) {
            $table->string('request_number')->unsigned()->index()->nullable();
            $table->string('invoice_number')->unsigned()->index()->nullable();
            $table->string('delivery_number')->unsigned()->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_stocks', function ($table) {
            $table->dropColumn('request_number');
            $table->dropColumn('invoice_number');
            $table->dropColumn('delivery_number');
        });
    }
}
