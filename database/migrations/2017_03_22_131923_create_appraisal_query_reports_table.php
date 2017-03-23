<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppraisalQueryReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appraisal_query_reports', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('hr_id')->index()->unsigned()->nullable();
            $table->integer('kip_id')->index()->unsigned()->nullable();
            $table->string('query_code')->index()->unsigned()->nullable();
            $table->string('voucher_verification_code')->index()->unsigned()->nullable();
            $table->string('query_type')->index()->unsigned()->nullable();
            $table->string('account_no')->index()->unsigned()->nullable();
            $table->string('account_name')->index()->unsigned()->nullable();
            $table->string('traveller_name')->index()->unsigned()->nullable();
            $table->string('departure_date')->index()->unsigned()->nullable();
            $table->string('supplier_name')->index()->unsigned()->nullable();
            $table->string('supplier_invoice_number')->index()->unsigned()->nullable();
            $table->string('created_by')->index()->unsigned()->nullable();
            $table->string('voucher_number')->index()->unsigned()->nullable();
            $table->string('invoice_date')->index()->unsigned()->nullable();
            $table->string('order_umber')->index()->unsigned()->nullable();
            $table->string('invoice_amount')->index()->unsigned()->nullable();
            $table->bigInteger('date_uploaded')->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('appraisal_query_reports');
    }
}
