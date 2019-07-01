<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnforceUploadProcurementApprovalStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('procurement_approval_steps', function (Blueprint $table) {
            $table->integer('enforce_upload')->nullable()->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procurement_approval_steps', function (Blueprint $table) {
            $table->dropColumn('enforce_upload');
		});
    }
}