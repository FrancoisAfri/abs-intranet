<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcurementRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurement_requests', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('employee_id')->unsigned()->index()->nullable();
			$table->integer('on_behalf_employee_id')->unsigned()->index()->nullable();
			$table->integer('on_behalf_of')->unsigned()->index()->nullable();
			$table->bigInteger('date_created')->unsigned()->index()->nullable();
            $table->string('title_name')->unsigned()->index()->nullable();
            $table->bigInteger('date_approved')->unsigned()->index()->nullable();
            $table->integer('status')->unsigned()->index()->nullable();
			$table->string('special_instructions')->unsigned()->index()->nullable();
			$table->string('detail_of_expenditure')->unsigned()->index()->nullable();
			$table->string('justification_of_expenditure')->unsigned()->index()->nullable();
			$table->string('po_number')->unsigned()->index()->nullable();
            $table->string('invoice_number')->unsigned()->index()->nullable();
            $table->string('delivery_number')->unsigned()->index()->nullable();
			$table->integer('request_collected')->unsigned()->index()->nullable();
			$table->integer('item_type')->unsigned()->index()->nullable();
			$table->integer('jobcard_id')->unsigned()->index()->nullable();
			$table->integer('stock_request_id')->unsigned()->index()->nullable();
			$table->string('collection_note')->unsigned()->index()->nullable();
            $table->string('collection_document')->unsigned()->index()->nullable();
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
        Schema::dropIfExists('procurement_requests');
    }
}