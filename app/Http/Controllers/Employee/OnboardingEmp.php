<?php

namespace App\Http\Controllers\Employee;

use App\Http\Requests\SendOnboardingLink;
use App\DivisionLevelFour;
use App\DivisionLevelThree;
use App\CompanyIdentity;
use App\DivisionLevelTwo;
use App\ManualClockin;
use App\TrainingDocuments;
use App\DivisionLevel;
use App\EmployeeTasks;
use App\HRPerson;
use App\Models\Onboarding;
use App\Http\Controllers\AuditReportsController;
use App\Http\Controllers\LeaveApplicationController;
use App\Models\AssetTransfers;
use App\Models\LicencesAllocation;
use App\Models\StoreRoom;
use App\Models\Video;
use App\modules;
use App\module_access;
use App\Province;
use App\employee_documents;
use App\Traits\BreadCrumpTrait;
use App\User;
use App\doc_type;
use App\Mail\OnboardingEmpMail;
use App\Mail\ConfirmRegistration;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
class OnboardingEmp extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $onboardings = Onboarding::where('status',1)
             ->orderBy('id')
            ->get();
		// arrays
		$MaritalStatus = [1 => 'Single',2 => 'Married',3 => 'Divorced',4 => 'Widower'];
        $ethnicity = [1 => 'African',2 => 'Asian',3 => 'Caucasian',4 => 'Coloured',5 => 'Indian',6 => 'White'];
        $leaveProfiles = [ 1 => 'Employee with no leave',2 => '5 Day Employee',3 => '6 Day Employee',4 => 'Shift Worker'];
        $titles = [ 1 => 'Mr',2 => 'Miss',3 => 'Ms',4 => 'Dr'];
        $disabilities = [ 1 => 'Yes',2 => 'No'];
        $employmentTypes = [ 1 => 'Permanent',2 => 'Temporary'];
        $occupationalLevels = [ 1 => 'Senior Management',2 => 'Middle Management',3 => 'Junior Management',4 => 'Semi Skilled',4 => 'Unskilled'];
        $jobFunctions = [ 1 => 'Mr',2 => 'Miss',3 => 'Ms',4 => 'Dr'];
		$marital_statuses = DB::table('marital_statuses')->where('status', 1)->orderBy('value', 'asc')->get();
        
		AuditReportsController::store(
            'Employee Records',
            'Onboarding Page Accessed',
            "Accessed By User",
            0
        );
		$data['active_mod'] = 'Employee Records';
        $data['active_rib'] = 'Onboarding';
        $data['onboardings'] = $onboardings;
		$data['titles'] = $titles;
        $data['disabilities'] = $disabilities;
        $data['employmentTypes'] = $employmentTypes;
        $data['occupationalLevels'] = $occupationalLevels;
        $data['jobFunctions'] = $jobFunctions;
        $data['marital_statuses'] = $marital_statuses;
        $data['ethnicity'] = $ethnicity;
        $data['statuses'] = Onboarding::STATUS_SELECT;
		
		$data['page_title'] = "Onboarding";
        $data['page_description'] = "Approvals";
        $data['breadcrumb'] = [
            ['title' => 'Employee Records', 'path' => 'employee/onboarding', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Onboarding', 'active' => 1, 'is_module' => 0]
        ];
        return view('Employees.onboarding')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SendOnboardingLink $request)
    {
		if (!empty($request['email']))
            Mail::to($request['email'])->send(new OnboardingEmpMail(
                $request['first_name']));
		
		AuditReportsController::store('Employee Records', "New Onboarding Saved", "Actioned By User", 0);
        
        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Onboarding $onboarding)
    {
		// arrays
		$provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();
		$MaritalStatus = [1 => 'Single',2 => 'Married',3 => 'Divorced',4 => 'Widower'];
        $ethnicity = [1 => 'African',2 => 'Asian',3 => 'Caucasian',4 => 'Coloured',5 => 'Indian',6 => 'White'];
        $leaveProfiles = [ 1 => 'Employee with no leave',2 => '5 Day Employee',3 => '6 Day Employee',4 => 'Shift Worker'];
        $titles = [ 1 => 'Mr',2 => 'Miss',3 => 'Ms',4 => 'Dr'];
        $disabilities = [ 1 => 'Yes',2 => 'No'];
        $employmentTypes = [ 1 => 'Permanent',2 => 'Temporary'];
        $occupationalLevels = [ 1 => 'Senior Management',2 => 'Middle Management',3 => 'Junior Management',4 => 'Semi Skilled',4 => 'Unskilled'];
        $jobFunctions = [ 1 => 'Mr',2 => 'Miss',3 => 'Ms',4 => 'Dr'];
		$marital_statuses = DB::table('marital_statuses')->where('status', 1)->orderBy('value', 'asc')->get();
        $ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
        $leave_profile = DB::table('leave_profile')->orderBy('name', 'asc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $businessCard = DB::table('business_card')->get();
        $positions = DB::table('hr_positions')->where('status', 1)->orderBy('name', 'asc')->get();
        
		AuditReportsController::store(
            'Employee Records',
            'Onboarding View Page Accessed',
            "Accessed By User",
            0
        );
		$data['active_mod'] = 'Employee Records';
        $data['active_rib'] = 'Onboarding';
        $data['positions'] = $positions;
        $data['employees'] = $employees;
        $data['leave_profile'] = $leave_profile;
        $data['onboarding'] = $onboarding;
		$data['provinces'] = $provinces;
		$data['titles'] = $titles;
        $data['disabilities'] = $disabilities;
        $data['employmentTypes'] = $employmentTypes;
        $data['occupationalLevels'] = $occupationalLevels;
        $data['jobFunctions'] = $jobFunctions;
        $data['marital_statuses'] = $marital_statuses;
        $data['ethnicity'] = $ethnicity;
        $data['ethnicities'] = $ethnicities;
        $data['statuses'] = Onboarding::STATUS_SELECT;
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();//->load('divisionLevelGroup');
		$data['division_levels'] = $divisionLevels;
		$data['page_title'] = "Onboarding";
        $data['page_description'] = "View";
        $data['breadcrumb'] = [
            ['title' => 'Employee Records', 'path' => 'employee/onboarding', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Onboarding', 'active' => 1, 'is_module' => 0]
        ];
        return view('Employees.view_onboarding')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SendOnboardingLink $request, Onboarding $onboarding)
    {
        if (isset($request['date_joined'])) {
            $request['date_joined'] = str_replace('/', '-', $request['date_joined']);
            $request['date_joined'] = strtotime($request['date_joined']);
        }
		if (isset($request['date_of_birth'])) {
            $request['date_of_birth'] = str_replace('/', '-', $request['date_of_birth']);
            $request['date_of_birth'] = strtotime($request['date_of_birth']);
        }
		if (isset($request['med_start_date'])) {
            $request['med_start_date'] = str_replace('/', '-', $request['med_start_date']);
            $request['med_start_date'] = strtotime($request['med_start_date']);
        }
		if (isset($request['provident_start_date'])) {
            $request['provident_start_date'] = str_replace('/', '-', $request['provident_start_date']);
            $request['provident_start_date'] = strtotime($request['provident_start_date']);
        }
		$request['status'] = 2;
		$onboarding->update($request->all());
		// creat user
		$compDetails = CompanyIdentity::first();
        $iduration = !empty($compDetails->password_expiring_month) ? $compDetails->password_expiring_month : 0;
        $expiredDate = !empty($iduration) ? mktime(0, 0, 0, date('m') + $iduration, date('d'), date('Y')) : 0;
        $password = "24247484";
		
		$user = new User;
        $user->email = $onboarding->email;
        $user->password = Hash::make($password);
        $user->type = 1;
        $user->status = 1;
        $user->password_changed_at = $expiredDate;
        $user->save();

		 //Save HR record
        $person = new HRPerson();
        $person->first_name = $onboarding->first_name;
        $person->surname = $onboarding->surname;
        $person->employee_number = $onboarding->employee_number;
        $person->job_function = $onboarding->job_function;
        $person->occupational_level = $onboarding->occupational_level;
        $person->employment_type = $onboarding->employment_type;
        $person->disabled = $onboarding->disabled;
        $person->nature_of_disability = !empty($onboarding->nature_of_disability) ? $onboarding->nature_of_disability : '';
        $person->provident_amount = !empty($onboarding->provident_amount) ? $onboarding->provident_amount : 0;
        $person->provident_name = !empty($onboarding->provident_name) ? $onboarding->provident_name : '';
        $person->provident_start_date =  !empty($onboarding->provident_start_date) ? $onboarding->provident_start_date : 0;
        $person->med_amount = !empty($onboarding->med_amount) ? $onboarding->med_amount : 0;
        $person->med_dep_kids = !empty($onboarding->med_dep_kids) ? $onboarding->med_dep_kids : '';
        $person->med_dep_adult = !empty($onboarding->med_dep_adult) ? $onboarding->med_dep_adult : '';
        $person->med_dep_spouse = !empty($onboarding->med_dep_spouse) ? $onboarding->med_dep_spouse : '';
        $person->med_plan_name = !empty($onboarding->med_plan_name) ? $onboarding->med_plan_name : '';
        $person->med_split = !empty($onboarding->med_split) ? $onboarding->med_split : '';
        $person->med_start_date = !empty($onboarding->med_start_date) ? $onboarding->med_start_date : 0;
        $person->second_manager_id = $onboarding->second_manager_id;
        $person->res_province_id = $onboarding->res_province_id;
        $person->res_postal_code = $onboarding->res_postal_code;
        $person->res_city = $onboarding->res_city;
        $person->res_suburb = $onboarding->res_suburb;
        $person->res_address = $onboarding->res_address;
        $person->gender = $onboarding->gender;
        $person->title = $onboarding->title;
        $person->position = $onboarding->position;
        $person->id_number = $onboarding->id_number;
        $person->date_of_birth = $onboarding->date_of_birth;
        $person->passport_number = !empty($onboarding->passport_number) ? $onboarding->passport_number : '';
        $person->initial = $onboarding->initial;
        $person->email = $onboarding->email;
        $person->cell_number = $onboarding->cell_number;
        $person->marital_status = $onboarding->marital_status;
        $person->ethnicity = $onboarding->ethnicity;
        $person->date_joined = $onboarding->date_joined;
        $person->manager_id = $onboarding->manager_id;
        $person->leave_profile = $onboarding->leave_profile;
        $person->known_as = $onboarding->known_as;
        $person->division_level_5 = !empty($onboarding->division_level_5) ? $onboarding->division_level_5 : 0;
        $person->division_level_4 = !empty($onboarding->division_level_4) ? $onboarding->division_level_4 : 0;
        $person->division_level_3 = !empty($onboarding->division_level_3) ? $onboarding->division_level_3 : 0;
        $person->next_of_kin_work_number = $onboarding->next_of_kin_work_number;
        $person->next_of_kin_number = $onboarding->next_of_kin_number;
        $person->account_number = !empty($onboarding->account_number) ? $onboarding->account_number : '';
        $person->branch_name = !empty($onboarding->branch_name) ? $onboarding->branch_name : '';
        $person->bank_name = !empty($onboarding->bank_name) ? $onboarding->bank_name : '';
        $person->account_holder_name = !empty($onboarding->account_holder_name) ? $onboarding->account_holder_name : '';
        $person->account_type = !empty($onboarding->account_type) ? $onboarding->account_type : '';
        $person->tax_office = !empty($onboarding->tax_office) ? $onboarding->tax_office : '';
        $person->income_tax_number = !empty($onboarding->income_tax_number) ? $onboarding->income_tax_number : '';
        $person->next_of_kin = $onboarding->next_of_kin;
        $person->division_level_2 = !empty($onboarding->division_level_2) ? $onboarding->division_level_2 : '';
        $person->division_level_1 = !empty($onboarding->division_level_1) ? $onboarding->division_level_1 : '';
        $person->status = 1;
		$user->addPerson($person);

        //Send email
        Mail::to("$user->email")->send(new ConfirmRegistration($user, $password));
        AuditReportsController::store('Security', 'New User Created', "Login Details Sent To User $user->email", 0);
        
		Alert::toast('New User Created, Email have been sent to user', 'success');
		//return back();
		return redirect()->route('onboarding.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	
	public function AcceptLeave(Request $request, leave_application $leaveId)
    {
        #query the hr person table
        $hrDetails = HRPerson::where('id', $leaveId->hr_id)->where('status', 1)->first();
        $daysApplied = $leaveId->leave_taken;
        //check leave approvals setup
        $approvals = leave_configuration::where('id', 1)
            ->select('require_managers_approval', 'require_department_head_approval')
            ->first();
        $ManHed = $approvals->require_managers_approval;
        $DepHead = $approvals->require_department_head_approval;
		
        //update leave application status
        if ($leaveId->status == 2 && $DepHead == 1) {
            $leaveId->status = 3;
            $leaveId->update();

        } elseif ($leaveId->status == 2 && $ManHed == 1) {
			
            // update leave application status
            $leaveId->status = 1;
            $leaveId->update();
            // #Query the  leave_config days for value
            $credit = leave_credit::getLeaveCredit($leaveId->hr_id, $leaveId->leave_type_id);
            $leaveBalance = $credit->leave_balance;
            #subract current balance from the one applied for
            $newBalance = $leaveBalance - $daysApplied;
            $credit->leave_balance = $newBalance;
            $credit->update();
            $leaveAttachment = $this->viewApplication($leaveId);
            #send email to the user informing that the leave has been accepted
            if (!empty($hrDetails->email))
                Mail::to($hrDetails->email)->send(new Accept_application($hrDetails->first_name, $leaveAttachment));

            // send emal to Hr manager / Hr managers
			// get user on setup
			$leaveNotificationsUsers = LeaveNotifications::getListOfUsers();
			if (!empty($leaveNotificationsUsers)) {
				foreach ($leaveNotificationsUsers as $user) {
					if (!empty($user->hr_id)) {
						$details = HRPerson::where('id', $user->hr_id)->where('status', 1)
							->select('first_name', 'email')->first();
						if (!empty($details->email))
							Mail::to($details->email)->send(new SendLeaveApplicationToHrManager($details->first_name, $leaveAttachment));
					}
				}
                
            }
            /*if (!empty($hrDetails->division_level_5)) {
                $Dept = DivisionLevelFive::where('id', $hrDetails->division_level_5)->first();
                if (!empty($Dept->manager_id)) {
                    $deptDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
                        ->select('first_name', 'email')
                        ->first();
                    if (!empty($deptDetails->email))
                        Mail::to($deptDetails->email)->send(new SendLeaveApplicationToHrManager($deptDetails->first_name, $leaveAttachment));
                }
            }*/
            // update leave history
            LeaveHistoryAuditController::store(
                "leave application Approved",
                '',
                $leaveBalance,
                $daysApplied,
                $newBalance,
                $leaveId->leave_type_id,
                $leaveId->hr_id
            );
        }

        AuditReportsController::store(
            'Leave Management',
            'leave_approval Informations accepted',
            "Edited by User: $leaveId->hr_id",
            0
        );

        return back()->with('success_application', "leave application was successful.");
    }

    /**
     * @param Request $request
     * @param leave_application $levReject
     * @return JsonResponse
     */
    public function reject(Request $request, leave_application $levReject)
    {
        $this->validate($request, [
            'description' => 'required',
        ]);
        $leaveData = $request->all();
        unset($leaveData['_token']);

        $usedetails = HRPerson::where(
            'id',
            $levReject->hr_id
        )
            ->select(
                'first_name',
                'surname',
                'email',
                'manager_id'
            )
            ->first();

        $manager_id = !empty($usedetails['manager_id']) ? $usedetails['manager_id'] : 0;

        $levReject->reject_reason = $request->input('description');
        $levReject->status = 6;
        $levReject->update();

        #send rejection email
        Mail::to($usedetails['email'])->send(
            new LeaveRejection
            (
                $usedetails['first_name']
                , $usedetails['surname']
                , $usedetails['email'])
        );

        AuditReportsController::store(
            'Leave Management: ',
            'leave rejected',
            "By User",
            0
        );

        LeaveHistoryAuditController::store(
            "leave application Rejected",
            0,
            0,
            0,
            0,
            $levReject->leave_type_id,
            $manager_id
        );
        return response()->json();
    }
}
