<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToHrPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('hr_people', function ($table) {
			$table->string('known_as')->nullable();
			$table->string('alternate_number')->nullable();
			$table->string('next_of_kin')->nullable();
			$table->string('next_of_kin_number')->nullable();
			$table->string('next_of_kin_work_number')->nullable();
			$table->string('complex_name')->nullable();
			$table->string('unit_number')->nullable();
			$table->string('income_tax_number')->nullable();
			$table->string('tax_office')->nullable();
			$table->string('account_type')->nullable();
			$table->string('account_holder_name')->nullable();
			$table->string('bank_name')->nullable();
			$table->string('branch_name')->nullable();
			$table->string('account_number')->nullable();
			$table->string('package_components')->nullable();
			$table->double('monthly_package')->nullable();
			$table->double('package_cash')->nullable();
			$table->double('package_travel')->nullable();
			$table->double('package_incentive')->nullable();
			$table->double('package_cell_phone')->nullable();
			$table->double('package_med_aid')->nullable();
			$table->string('med_start_date')->nullable();
			$table->string('med_split')->nullable();
			$table->string('med_plan_name')->nullable();
			$table->string('med_dep_spouse')->nullable();
			$table->string('med_dep_adult')->nullable();
			$table->string('med_dep_kids')->nullable();
			$table->double('med_amount')->nullable();
			$table->string('provident_start_date')->nullable();
			$table->string('provident_name')->nullable();
			$table->double('provident_amount')->nullable();
            $table->integer('disabled')->unsigned()->nullable();
            $table->string('nature_of_disability')->unsigned()->nullable();
            $table->integer('citizenship')->unsigned()->nullable();
            $table->string('other')->unsigned()->nullable();
            $table->integer('employment_type')->unsigned()->nullable();
            $table->integer('occupational_level')->unsigned()->nullable();
            $table->integer('job_function')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_people', function ($table) {
            $table->dropColumn('title');
            $table->dropColumn('known_as');
            $table->dropColumn('alternate_number');
            $table->dropColumn('next_of_kin');
            $table->dropColumn('next_of_kin_number');
            $table->dropColumn('next_of_kin_work_number');
            $table->dropColumn('complex_name');
            $table->dropColumn('unit_number');
            $table->dropColumn('income_tax_number');
            $table->dropColumn('tax_office');
            $table->dropColumn('account_type');
            $table->dropColumn('account_holder_name');
            $table->dropColumn('bank_name');
            $table->dropColumn('branch_name');
            $table->dropColumn('account_number');
            $table->dropColumn('package_components');
            $table->dropColumn('monthly_package');
            $table->dropColumn('package_cash');
            $table->dropColumn('package_travel');
            $table->dropColumn('package_incentive');
            $table->dropColumn('package_cell_phone');
            $table->dropColumn('package_med_aid');
            $table->dropColumn('med_start_date');
            $table->dropColumn('med_split');
            $table->dropColumn('med_plan_name');
            $table->dropColumn('med_dep_spouse');
            $table->dropColumn('med_dep_adult');
            $table->dropColumn('med_dep_kids');
            $table->dropColumn('med_amount');
            $table->dropColumn('provident_start_date');
            $table->dropColumn('provident_name');
            $table->dropColumn('provident_amount');
            $table->dropColumn('disabled');
            $table->dropColumn('nature_of_disability');
            $table->dropColumn('citizenship');
            $table->dropColumn('other');
            $table->dropColumn('employment_type');
            $table->dropColumn('occupational_level');
            $table->dropColumn('job_function');
        });
    }
}
