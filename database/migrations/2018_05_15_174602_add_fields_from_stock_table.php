<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsFromStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('stock', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->index()->nullable();
            $table->unsignedInteger('category_id')->index()->nullable();
            $table->unsignedInteger('avalaible_stock')->index()->nullable();
            $table->unsignedBigInteger('date_added')->index()->nullable();
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
            $table->dropColumn('product_id');
            $table->dropColumn('category_id');
            $table->dropColumn('avalaible_stock');
            $table->dropColumn('date_added');
        });
    }
}
