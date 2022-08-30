<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcurementQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurement_quotations', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('procurement_id')->nullable();
			$table->integer('supplier_id')->nullable();
			$table->integer('contact_id')->nullable();
			$table->double('total_cost')->nullable();
			$table->string('attachment')->nullable();
			$table->string('comment')->nullable();
            $table->bigInteger('date_added')->nullable();
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
        Schema::dropIfExists('procurement_quotations');
    }
}
