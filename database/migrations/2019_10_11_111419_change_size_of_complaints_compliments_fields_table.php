<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSizeOfComplaintsComplimentsFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaints_compliments', function (Blueprint $table) {
            $table->longText('office', 5000)->change();
            $table->longText('error_type', 5000)->change();
            $table->longText('pending_reason', 5000)->change();
            $table->longText('summary_corrective_measure', 5000)->change();
            $table->longText('summary_complaint_compliment', 5000)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
