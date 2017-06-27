<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnsInAppraisalSetupTo360AppraisalSetupFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appraisal_setup', function (Blueprint $table) {
            $table->dropColumn('number_of_times');
            $table->dropColumn('percentage');
            $table->dropColumn('active');

            $table->double('manager_appraisal_weight')->nullable();
            $table->double('employee_appraisal_weight')->nullable();
            $table->double('colleagues_appraisal_weight')->nullable();
            $table->smallInteger('three_sixty_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appraisal_setup', function (Blueprint $table) {
            $table->integer('number_of_times')->nullable();
            $table->double('percentage')->nullable();
            $table->smallInteger('active')->nullable();

            $table->dropColumn('manager_appraisal_weight');
            $table->dropColumn('employee_appraisal_weight');
            $table->dropColumn('colleagues_appraisal_weight');
            $table->dropColumn('three_sixty_status');
        });
    }
}
