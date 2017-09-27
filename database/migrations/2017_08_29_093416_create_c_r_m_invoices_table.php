<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCRMInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_r_m_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quotation_id')->unsigned()->index()->nullable();
            $table->integer('client_id')->unsigned()->index()->nullable();
            $table->integer('company_id')->unsigned()->index()->nullable();
            $table->integer('account_id')->unsigned()->index()->nullable();
            $table->bigInteger('invoice_number')->nullable();
            $table->double('amount')->nullable();
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
        Schema::dropIfExists('c_r_m_invoices');
    }
}
