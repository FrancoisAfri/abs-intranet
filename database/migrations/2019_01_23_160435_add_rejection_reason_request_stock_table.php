<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRejectionReasonRequestStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_stocks', function ($table) {
            $table->string('rejection_reason')->index()->nullable();
            $table->unsignedInteger('rejected_by')->index()->nullable();
            $table->unsignedBigInteger('rejection_date')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_stocks', function ($table) {
            $table->dropColumn('rejection_reason');
            $table->dropColumn('rejected_by');
            $table->dropColumn('rejection_date');
        });
    }
}
