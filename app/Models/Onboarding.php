<?php

namespace App\Models;

use App\HRPerson;
use App\Traits\Uuids;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;

class Onboarding extends Model
{
	
    protected $table = 'hr_people_temp';
	
	/**
     * @var string[]
     */
    protected $hidden = [
        'id'
    ];

    protected $fillable = ['employee_number','job_function', 'occupational_level', 'employment_type', 'status', 'other'
		, 'citizenship', 'nature_of_disability', 'disabled', 'provident_amount'
		, 'provident_name', 'provident_start_date', 'med_amount', 'med_dep_kids'
		, 'med_dep_adult', 'med_dep_spouse', 'med_plan_name', 'med_split', 'med_start_date'
		, 'package_med_aid', 'package_cell_phone', 'package_incentive', 'package_travel'
		, 'monthly_package', 'package_components', 'account_number', 'branch_name', 'bank_name'
		, 'account_holder_name', 'account_type', 'tax_office', 'income_tax_number', 'unit_number'
		, 'complex_name', 'next_of_kin_work_number', 'next_of_kin_number', 'next_of_kin', 'alternate_number'
		, 'known_as', 'division_level_5', 'division_level_4', 'division_level_3', 'division_level_2', 'division_level_1'
		, 'leave_profile', 'manager_id', 'date_joined', 'date_left', 'ethnicity', 'marital_status'
		, 'own_transport','first_name', 'surname', 'middle_name', 'maiden_name', 'aka', 'initial', 'email', 'cell_number',
        'phone_number', 'id_number', 'date_of_birth', 'passport_number', 'drivers_licence_number', 'drivers_licence_code',
        'proof_drive_permit', 'proof_drive_permit_exp_date', 'drivers_licence_exp_date', 'gender', 'title', 'position',
        'res_address', 'res_suburb', 'res_city', 'res_postal_code', 'res_province_id', 'second_manager_id'];
	
	 /**
     * status constants
     */
    const STATUS_SELECT = [
        1 => 'Awaiting Approval',
        2 => 'Approved',
        3 => 'Rejected',
    ];
	
	public function province()
    {
        return $this->belongsTo(Province::class, 'res_province_id');
    }
}
