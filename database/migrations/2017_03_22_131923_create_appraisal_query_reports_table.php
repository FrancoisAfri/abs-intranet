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
			$table->unsignedInteger('hr_id')->index()->nullable();
            $table->foreign('hr_id')
                ->references('id')->on('hr_people')
                ->onDelete('cascade');
            $table->unsignedInteger('kip_id')->index()->nullable();
            $table->foreign('kip_id')
                ->references('id')->on('appraisals_kpis')
                ->onDelete('cascade');

            $table->string('query_code')->index()->nullable();
            $table->string('voucher_verification_code')->index()->nullable();
            $table->string('query_type')->index()->nullable();
            $table->string('account_no')->index()->nullable();
            $table->string('account_name')->index()->nullable();
            $table->string('traveller_name')->index()->nullable();
            $table->string('departure_date')->index()->nullable();
            $table->string('supplier_name')->index()->nullable();
            $table->string('supplier_invoice_number')->index()->nullable();
            $table->string('created_by')->index()->nullable();
            $table->string('voucher_number')->index()->nullable();
            $table->string('invoice_date')->index()->nullable();
            $table->string('order_umber')->index()->nullable();
            $table->string('invoice_amount')->index()->nullable();
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
