<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrPeopleChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_people_changes', function (Blueprint $table) {
			$table->increments('id');
			$table->uuid('uuid')->index();
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('surname')->nullable();
            $table->string('employee_number')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('maiden_name')->nullable();
            $table->string('aka')->nullable();
            $table->string('initial')->nullable();
            $table->unsignedInteger('position')->index()->nullable();
            $table->string('email')->nullable();
            $table->string('cell_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('id_number')->nullable();
            $table->string('res_address')->nullable();
            $table->string('res_suburb')->nullable();
            $table->string('res_city')->nullable();
            $table->integer('res_postal_code')->nullable();
            $table->integer('res_province_id')->nullable();
            $table->integer('res_country_id')->nullable();
            $table->bigInteger('date_of_birth')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('drivers_licence_number')->nullable();
            $table->string('drivers_licence_code')->nullable();
            $table->string('proof_drive_permit', 100)->nullable();
            $table->bigInteger('proof_drive_permit_exp_date')->nullable();
            $table->bigInteger('drivers_licence_exp_date')->nullable();
            $table->smallInteger('gender')->nullable();
            $table->smallInteger('own_transport')->nullable();
            $table->integer('marital_status')->nullable();
            $table->integer('ethnicity')->nullable();
            $table->string('profile_pic')->nullable();
            $table->smallInteger('status')->nullable();
			$table->bigInteger('date_left')->nullable();
			$table->bigInteger('date_joined')->nullable();
			$table->integer('manager_id')->unsigned()->index()->nullable();
			$table->integer('leave_profile')->unsigned()->index()->nullable();
			$table->integer('division_level_1')->unsigned()->index()->nullable();
            $table->integer('division_level_2')->unsigned()->index()->nullable();
            $table->integer('division_level_3')->unsigned()->index()->nullable();
            $table->integer('division_level_4')->unsigned()->index()->nullable();
            $table->integer('division_level_5')->unsigned()->index()->nullable();
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
			$table->string('med_split')->nullable();
			$table->string('med_plan_name')->nullable();
			$table->string('med_dep_spouse')->nullable();
			$table->string('med_dep_adult')->nullable();
			$table->string('med_dep_kids')->nullable();
			$table->bigInteger('provident_start_date')->nullable();
			$table->string('provident_name')->nullable();
			$table->double('provident_amount')->nullable();
            $table->integer('disabled')->unsigned()->nullable();
            $table->string('nature_of_disability')->unsigned()->nullable();
            $table->integer('citizenship')->unsigned()->nullable();
            $table->integer('employment_type')->unsigned()->nullable();
            $table->integer('occupational_level')->unsigned()->nullable();
            $table->integer('job_function')->unsigned()->nullable();
			$table->bigInteger('med_start_date')->nullable();
			$table->boolean('is_approved')->default(false); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hr_people_changes');
    }
}
