a<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppraiserIdToAppraisalKPIResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appraisal_k_p_i_results', function($table) {
            $table->integer('appraiser_id')->index()->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appraisal_k_p_i_results', function($table) {
            $table->dropColumn('appraiser_id');
        });
    }
}
