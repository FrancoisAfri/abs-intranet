<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveOnboardingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'title' => 'required',
           'first_name' => 'required',
           'surname' => 'required',
           'known_as' => 'required',
           'initial' => 'required',
           'cell_number' => 'required',
           'email' => 'unique:users,email',
           'email' => 'unique:hr_people,email',
           'res_address' => 'required',
           'res_suburb' => 'required',
           'res_city' => 'required',
           'res_postal_code' => 'required',
           'res_province_id' => 'required',
           'date_of_birth' => 'required',
           'gender' => 'required',
           'id_number' => 'unique:hr_people,id_number',
           'passport_number' => 'unique:hr_people,passport_number',
           'marital_status' => 'required',
           'ethnicity' => 'required',
           'next_of_kin' => 'required',
           'next_of_kin_number' => 'required',
           'next_of_kin_work_number' => 'required',
           'income_tax_number' => 'required',
           'tax_office' => 'required',
           'account_type' => 'required',
           'account_holder_name' => 'required',
           'bank_name' => 'required',
           'branch_name' => 'required',
           'account_number' => 'required',
           'med_start_date' => 'required',
           'med_split' => 'required',
           'med_plan_name' => 'required',
           'med_dep_spouse' => 'required',
           'med_dep_adult' => 'required',
           'med_dep_kids' => 'required',
           'provident_start_date' => 'required',
           'provident_amount' => 'required',
           'provident_name' => 'required',
           'date_joined' => 'required',
           'leave_profile' => 'required',
           'position' => 'required',
           'manager_id' => 'required',
           'second_manager_id' => 'required',
           'disabled' => 'required',
           'nature_of_disability' => 'required',
           'employment_type' => 'required',
           'occupational_level' => 'required',
           'job_function' => 'required',
        ];
    }
}
