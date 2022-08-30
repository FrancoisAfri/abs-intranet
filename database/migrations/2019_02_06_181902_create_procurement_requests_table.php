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
			$table->unsignedInteger('employee_id')->index()->nullable();
			$table->unsignedInteger('on_behalf_employee_id')->index()->nullable();
			$table->unsignedInteger('on_behalf_of')->index()->nullable();
			$table->unsignedBigInteger('date_created')->index()->nullable();
            $table->string('title_name')->index()->nullable();
            $table->unsignedBigInteger('date_approved')->index()->nullable();
            $table->unsignedInteger('status')->index()->nullable();
			$table->string('special_instructions')->index()->nullable();
			$table->string('detail_of_expenditure')->index()->nullable();
			$table->string('justification_of_expenditure')->index()->nullable();
			$table->string('po_number')->index()->nullable();
            $table->string('invoice_number')->index()->nullable();
            $table->string('delivery_number')->index()->nullable();
			$table->unsignedInteger('request_collected')->index()->nullable();
			$table->unsignedInteger('item_type')->index()->nullable();
			$table->unsignedInteger('jobcard_id')->index()->nullable();
			$table->unsignedInteger('stock_request_id')->index()->nullable();
			$table->string('collection_note')->index()->nullable();
            $table->string('collection_document')->index()->nullable();
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