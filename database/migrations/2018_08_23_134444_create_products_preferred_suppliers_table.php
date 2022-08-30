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
			$table->unsignedInteger('order_no')->index()->nullable();
			$table->unsignedInteger('supplier_id')->index()->nullable();
			$table->unsignedInteger('status')->index()->nullable();
            $table->double('mass_net')->unsigned()->index()->nullable();
            $table->string('description')->index()->nullable();
            $table->string('inventory_code')->index()->nullable();
            $table->string('commodity_code')->index()->nullable();
            $table->unsignedBigInteger('date_last_processed')->index()->nullable();
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
