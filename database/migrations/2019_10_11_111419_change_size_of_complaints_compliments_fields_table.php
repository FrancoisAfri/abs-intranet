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
            $table->string('office', 5000)->change();
            $table->string('error_type', 5000)->change();
            $table->string('pending_reason', 5000)->change();
            $table->string('summary_corrective_measure', 5000)->change();
            $table->string('summary_complaint_compliment', 5000)->change();
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
