<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameAndPriceColumsToPackagesProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages_product_table', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('description')->nullable();
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
        Schema::table('packages_product_table', function (Blueprint $table) {
            //
        });
    }
}
