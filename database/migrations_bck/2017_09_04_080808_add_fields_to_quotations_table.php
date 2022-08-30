<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->smallInteger('payment_option')->nullable();
            $table->integer('payment_term')->nullable();
            $table->bigInteger('first_payment_date')->nullable();
            $table->integer('account_id')->unsigned()->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('payment_option');
            $table->dropColumn('payment_term');
            $table->dropColumn('first_payment_date');
            $table->dropColumn('account_id');
        });
    }
}
