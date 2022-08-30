<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDirectoriesLocationToVehicleConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_configuration', function (Blueprint $table) {
            $table->string('brake_test_from')->nullable()->unsigned()->index();
            $table->string('brake_test_to')->nullable()->unsigned()->index();
            $table->string('fire_extinguisher_from')->nullable()->unsigned()->index();
            $table->string('fire_extinguisher_to')->nullable()->unsigned()->index();
            $table->string('fuel_transaction_from')->nullable()->unsigned()->index();
            $table->string('fuel_transaction_to')->nullable()->unsigned()->index();
            $table->string('get_fitment_from')->nullable()->unsigned()->index();
            $table->string('get_fitment_to')->nullable()->unsigned()->index();
            $table->string('ldv_car_inspection_from')->nullable()->unsigned()->index();
            $table->string('ldv_car_inspection_to')->nullable()->unsigned()->index();
            $table->string('ldv_pre_use_inspections_from')->nullable()->unsigned()->index();
            $table->string('ldv_pre_use_inspections_to')->nullable()->unsigned()->index();
            $table->string('mechanic_plant_inspections_from')->nullable()->unsigned()->index();
            $table->string('mechanic_plant_inspections_to')->nullable()->unsigned()->index();
            $table->string('truck_tractor_rigid_chassis_from')->nullable()->unsigned()->index();
            $table->string('truck_tractor_rigid_chassis_to')->nullable()->unsigned()->index();
            $table->string('tyre_survey_reports_from')->nullable()->unsigned()->index();
            $table->string('tyre_survey_reports_to')->nullable()->unsigned()->index();
            $table->string('job_card_inspection_from')->nullable()->unsigned()->index();
            $table->string('job_card_inspection_to')->nullable()->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_configuration', function (Blueprint $table) {
            $table->dropColumn('brake_test_from');
            $table->dropColumn('brake_test_to');
            $table->dropColumn('fire_extinguisher_from');
            $table->dropColumn('fire_extinguisher_to');
            $table->dropColumn('fuel_transaction_from');
            $table->dropColumn('fuel_transaction_to');
            $table->dropColumn('get_fitment_from');
            $table->dropColumn('get_fitment_to');
            $table->dropColumn('ldv_car_inspection_from');
            $table->dropColumn('ldv_car_inspection_to');
            $table->dropColumn('ldv_pre_use_inspections_from');
            $table->dropColumn('ldv_pre_use_inspections_to');
            $table->dropColumn('mechanic_plant_inspections_from');
            $table->dropColumn('mechanic_plant_inspections_to');
            $table->dropColumn('truck_tractor_rigid_chassis_from');
            $table->dropColumn('truck_tractor_rigid_chassis_to');
            $table->dropColumn('tyre_survey_reports_from');
            $table->dropColumn('tyre_survey_reports_to');
            $table->dropColumn('job_card_inspection_from');
            $table->dropColumn('job_card_inspection_to');
		});
    }
}
