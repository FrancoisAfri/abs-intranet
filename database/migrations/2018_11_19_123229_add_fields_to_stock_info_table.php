<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToStockInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_infos', function ($table) {
            $table->unsignedInteger('stock_level_1')->index()->nullable();
            $table->unsignedInteger('stock_level_5')->index()->nullable();
            $table->unsignedInteger('stock_level_4')->index()->nullable();
            $table->unsignedInteger('stock_level_3')->index()->nullable();
            $table->unsignedInteger('stock_level_2')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_infos', function ($table) {
            $table->dropColumn('stock_level_5');
            $table->dropColumn('stock_level_4');
            $table->dropColumn('stock_level_3');
            $table->dropColumn('stock_level_2');
            $table->dropColumn('stock_level_1');
        });
    }
}
