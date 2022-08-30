<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToStockInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_infos', function ($table) {
            $table->integer('allow_vat')->unsigned()->index()->nullable();
            $table->double('mass_net')->unsigned()->index()->nullable();
			$table->double('minimum_level')->unsigned()->index()->nullable();
            $table->double('maximum_level')->unsigned()->index()->nullable();
            $table->string('bar_code')->unsigned()->index()->nullable();
            $table->string('unit')->unsigned()->index()->nullable();
            $table->string('commodity_code')->unsigned()->index()->nullable();
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
            $table->dropColumn('bar_code');
            $table->dropColumn('unit');
            $table->dropColumn('mass_net');
            $table->dropColumn('commodity_code');
            $table->dropColumn('allow_vat');
            $table->dropColumn('minimum_level');
            $table->dropColumn('maximum_level');
        });
    }
}