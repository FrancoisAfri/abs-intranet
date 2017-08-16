<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameAndPriceColumsToProductPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages_product_table', function (Blueprint $table) {
            //
            $table->string('name')->nullable();
            $table->double('price')->nullable();
            $table->smallInteger('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_packages', function (Blueprint $table) {
            //
        });
    }
}
