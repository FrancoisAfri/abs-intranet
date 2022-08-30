<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsPreferredSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_preferred_suppliers', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('order_no')->unsigned()->index()->nullable();
			$table->integer('supplier_id')->unsigned()->index()->nullable();
			$table->integer('status')->unsigned()->index()->nullable();
            $table->double('mass_net')->unsigned()->index()->nullable();
            $table->string('description')->unsigned()->index()->nullable();
            $table->string('inventory_code')->unsigned()->index()->nullable();
            $table->string('commodity_code')->unsigned()->index()->nullable();
            $table->bigInteger('date_last_processed')->unsigned()->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_preferred_suppliers');
    }
}
