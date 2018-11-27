<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToStockSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_settings', function ($table) {
            $table->integer('require_managers_approval')->unsigned()->index()->nullable();
            $table->integer('require_store_manager_approval')->unsigned()->index()->nullable();
            $table->integer('require_department_head_approval')->unsigned()->index()->nullable();
            $table->integer('require_ceo_approval')->unsigned()->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_settings', function ($table) {
            $table->dropColumn('require_managers_approval');
            $table->dropColumn('require_store_manager_approval');
            $table->dropColumn('require_department_head_approval');
            $table->dropColumn('require_ceo_approval');
        });
    }
}
