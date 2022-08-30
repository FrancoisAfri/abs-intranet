<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceDateToCRMInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_r_m_invoices', function (Blueprint $table) {
            $table->bigInteger('invoice_date')->nullable();
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
        Schema::table('c_r_m_invoices', function (Blueprint $table) {
            $table->dropColumn('invoice_date');
            $table->dropColumn('status');
        });
    }
}
