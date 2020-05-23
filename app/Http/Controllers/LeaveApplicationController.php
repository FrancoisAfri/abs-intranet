<?php

namespace App\Http\Controllers;

use App\DivisionLevelTwo;
use App\hr_person;
use App\HRPerson;
use App\CompanyIdentity;
use App\Http\Requests;
use APP\leavDetails;
use App\DivisionLevelFive;
use App\leave_application;
use App\leave_configuration;
use App\leave_credit;
use App\leave_custom;
use App\leave_history;
use App\LeaveType;
use App\Mail\Accept_application;
use App\Mail\SendLeaveApplicationToManager;
use App\Mail\SendLeaveApplicationToHrManager;
use App\Mail\leave_applications;
use App\Mail\LeaveRejection;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;

class LeaveApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {		
        $leave_customs = leave_custom::orderBy('hr_id', 'asc')->get();
        if (!empty($leave_customs))
            $leave_customs = $leave_customs->load('userCustom');

		$hrID = Auth::user()->id;
		$currentUser = Auth::user()->person->id;
		$userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'leave')->where('security_modules_access.access_level', '>', 3)
            ->where('security_modules_access.user_id', $hrID)->pluck('user_id')->first();  
		if (!empty($userAccess))
			$employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
		else
		{
			$reportsTo = HRPerson::where('status', 1)->where('manager_id', $currentUser)->orwhere('id', $currentUser)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
			if ($reportsTo->count() > 0) 
				$employees = $reportsTo;
			else
				$employees = HRPerson::where('status', 1)->where('id', $currentUser)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
		}
        $leaveTypes = LeaveType::where('status', 1)->orderBy('name', 'asc')->get()->load(['leave_profle' => function ($query) {
           
        }]);
        #Query to get negative annual leave days for user based on userID and LeaveID
        $negativeannualDays = DB::table('leave_configuration')
            ->select('allow_annual_negative_days')
            ->where('id', 1)
            ->where('allow_annualLeave_credit', 1)
            ->get();
        $negannualDays = !empty($negativeannualDays->first()->allow_annual_negative_days) ? $negativeannualDays->first()->allow_annual_negative_days: 0;
        #Query to get negative sick leave days for user based on userID and LeaveID
        $negativesickDays = DB::table('leave_configuration')
            ->select('allow_sick_negative_days')
            ->where('id', 1)
            ->where('allow_sickLeave_credit', 1)
            ->get();
        $negsickDays = !empty($negativesickDays->first()->allow_sick_negative_days) ? $negativesickDays->first()->allow_sick_negative_days : 0;;

        $data['negannualDays'] = $negannualDays;
        $data['negsickDays'] = $negsickDays;
        $data['leaveTypes'] = $leaveTypes;
        $data['employees'] = $employees;
        $data['leave_customs'] = $leave_customs;
		
		$data['page_title'] = "leave Management";
        $data['page_description'] = "Leave Application";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Leave Application', 'active' => 1, 'is_module' => 0]
        ];
		$data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Apply';

        AuditReportsController::store('Leave Management', 'Leave Type Page Accessed', "Accessed By User", 0);
        return view('leave.application')->with($data);
    }
	
	public function getAllSubordinates($users,$managerID)
	{
		$employees = HRPerson::where('status', 1)->where('manager_id', $managerID)->pluck('id');

		foreach ($employees as $employee) 
		{
			if (array_key_exists($employee,$users)) continue;
			if ($employee == $managerID) continue;
			$users[] = $employee;
			$users = LeaveApplicationController::getAllSubOrdinates($users,$employee);
		}
		return $users;
	}

    public function show()
    {
        $data['page_title'] = "leave Management";
        $data['page_description'] = "Leave Approvals";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => 'leave/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'leave Approval', 'active' => 1, 'is_module' => 0]
        ];
		$loggedInEmplID = Auth::user()->person->id;
		$subordinates = LeaveApplicationController::getAllSubordinates(array(),$loggedInEmplID);

		$people = DB::table('hr_people')->orderBy('id', 'asc')->get();
        $leaveTypes = LeaveType::where('status', 1)->get()->load(['leave_profle' => function ($query) {
            $query->orderBy('name', 'asc');
        }]);
        // left join between leaveApplication & HRPerson & LeaveType

        $leaveStatus = array(1 => 'Approved', 2 => 'Require managers approval ', 3 => 'Require department head approval', 4 => 'Require hr approval', 5 => 'Require payroll approval', 6 => 'rejected', 7 => 'rejectd_by_department_head', 8 => 'rejectd_by_hr', 9 => 'rejectd_by_payroll', 10 => 'Cancelled');
        $leaveApplications = DB::table('leave_application')
            ->select('leave_application.*'
			, 'hr_people.first_name as firstname'
			, 'hr_people.surname as surname'
			, 'hp.first_name as mg_firstname'
			, 'hp.surname as mg_surname'
			, 'leave_types.name as leavetype'
			, 'hr_people.manager_id as manager')
            ->leftJoin('hr_people', 'leave_application.hr_id', '=', 'hr_people.id')
            ->leftJoin('leave_types', 'leave_application.leave_type_id', '=', 'leave_types.id')
            ->leftJoin('hr_people as hp', 'leave_application.manager_id', '=', 'hp.id')
            ->where('hr_people.manager_id', $loggedInEmplID)
            ->whereNotIn('leave_application.status', [1, 6, 7, 8, 9, 10])
            ->orderBy('leave_application.hr_id')
            ->orderBy('leave_application.id')
            ->get();
			
		// get all surbodinates applicatiions
		$subLeaveApplications = DB::table('leave_application')
            ->select('leave_application.*'
			, 'hr_people.first_name as firstname'
			, 'hr_people.surname as surname'
			, 'leave_types.name as leavetype'
			, 'hp.first_name as mg_firstname'
			, 'hp.surname as mg_surname'
			, 'hr_people.manager_id as manager')
            ->leftJoin('hr_people', 'leave_application.hr_id', '=', 'hr_people.id')
            ->leftJoin('leave_types', 'leave_application.leave_type_id', '=', 'leave_types.id')
			->leftJoin('hr_people as hp', 'leave_application.manager_id', '=', 'hp.id')
			->whereIn('hr_people.manager_id', $subordinates)
            ->whereNotIn('leave_application.status', [1, 6, 7, 8, 9, 10])
            ->orderBy('leave_application.hr_id')
            ->orderBy('leave_application.id')
            ->get();
		
		$data['leaveStatus'] = $leaveStatus;
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Approval';
        $data['leaveTypes'] = $leaveTypes;
        $data['leaveApplications'] = $leaveApplications;
        $data['subLeaveApplications'] = $subLeaveApplications;

        AuditReportsController::store('Leave Management', 'Leave Approval Page Accessed', "Accessed By User", 0);
        return view('leave.leave_approval')->with($data);
    }

    public function cancelApplication(Request $request, leave_application $leaveApplication)
    {
        if ($leaveApplication && in_array($leaveApplication->status, [2, 3, 4, 5])) {
            $this->validate($request, [
                'cancellation_reason' => 'required'
            ]);
            $user = Auth::user()->load('person');
            $leaveApplication->status = 10;
            $leaveApplication->canceller_id = $user->person->id;
            $leaveApplication->cancellation_reason = $request->input('cancellation_reason');
            $leaveApplication->update();

            return response()->json(['success' => 'Leave application successfully cancelled.'], 200);
        }
    }

    public static function status($status = 0)
    {
        //$approvalstatus = array(1 => 'Approved', 2 => 'require_managers_approval ', 3 => 'require_department_head_approval', 4 => 'require_hr_approval', 5 => 'require_payroll_approval', 6 => 'Approved', 7 => 'Rejected');
        //$rejectstatus = array(7 => 'rejectd_by_managers ', 8 => 'rejectd_by_department_head', 9 => 'rejectd_by_hr', 10 => 'rejectd_by_payroll');
        $leaveStatusNames = [1 => 'Approved', 2 => 'Require managers approval ', 3 => 'Require department head approval', 4 => 'Require HR approval', 5 => 'Require payroll approval', 6 => 'Rejected', 7 => 'Rejected by department head', 8 => 'Rejected by HR', 9 => 'Rejected by payroll', 10 => 'Cancelled'];
        if ($status && $status > 0) return $leaveStatusNames[$status];
        else return $leaveStatusNames;
    }

    public function ApplicationDetails($status = 0, $hrID = 0)
    {
        // query the leave congif table and bring back the values
        $approvals = DB::table('leave_configuration')
            ->select('require_managers_approval', 'require_department_head_approval', 'require_hr_approval', 'require_payroll_approval')
            ->first();
        // query the hrperon  model and bring back the values of the managerg
        $hrDetails = HRPerson::where('id', $hrID)->where('status', 1)->first();

        if ($approvals->require_managers_approval == 1) {
            # code...
            // query the hrperon  model and bring back the values of the manager
            $managerDetails = HRPerson::where('id', $hrDetails->manager_id)->where('status',1)
                ->select('first_name', 'surname', 'email')
                ->first();
			// array to store manager details
			$details = array('status' => 2, 'first_name' => $managerDetails->first_name, 'surname' => $managerDetails->surname, 'email' => $managerDetails->email);
			return $details;
        }
		elseif ($approvals->require_department_head_approval == 1) {
            # code...  division_level_twos
            // query the hrperon  model and bring back the values of the manager
            $Dept = DivisionLevelTwo::where('id', $hrDetails->division_level_2)->get()->first();
            $deptDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
                ->select('first_name', 'surname', 'email')
                ->first();
			// array to store manager details
			$details = array('status' => 3, 'first_name' => $deptDetails->first_name, 'surname' => $deptDetails->surname, 'email' => $deptDetails->email);
			return $details;
        } #code here .. Require Hr
    }

    #function to get available days for user based on userID and LeaveID

    public function availableDays($hrID, $typID)
    {
		$row = leave_configuration::first();
		$numberAnnual = !empty($row->allow_annual_negative_days) ? $row->allow_annual_negative_days : 0;
		$numberSick = !empty($row->allow_sick_negative_days) ? $row->allow_sick_negative_days : 0;
		$extraDays = 0; 
        $balance = DB::table('leave_credit')
            ->select('leave_balance')
            ->where('hr_id', $hrID)
            ->where('leave_type_id', $typID)
            ->get();
		if ($typID == 1) $extraDays = $numberAnnual;
		elseif ($typID == 5) $extraDays = $numberSick;
        $leaveDays =  !empty($balance->first()->leave_balance) ? $balance->first()->leave_balance / 8: 0;
        return  $leaveDays /*+ $extraDays*/;
    }

    # calculate leave days
	public function calculatedays($dateFrom, $dateTo)
    {
        //convert dates
        $startDate = strtotime($dateFrom);
        $endDate = strtotime($dateTo);
		$onceOffHoliday = date("Y", $startDate);
        // calculate public holidays and weekends
        $numweek = 0;
        $publicHolidays = array();
        $publicHolidays = DB::table('public_holidays')
							->where(function ($query)  use ($onceOffHoliday) {
								$query->whereNull('year')
									  ->orWhere('year', '=', 0)
									  ->orWhere('year', '=', $onceOffHoliday);
							})
							->pluck('day');
        # Add Easter Weekend to list of public holidays
		$easterSunday =  easter_date(date("Y",$endDate));
		$publicHolidays[] = $easterSunday - (2*3600*24);
		$publicHolidays[] = $easterSunday + (3600*24);
		
		for ($i = $startDate; $i <= $endDate; $i = $i+86400)
		{
			$publicArray = array();
			foreach ($publicHolidays as $key => $value)
			{
				$day = date("Y",$i)."-".date("m",$value)."-".date("d",$value);
				$day = strtotime($day);
				$publicArray[$day] = 0;
			}
			if (((date("w",$i) == 6) || (date("w",$i) == 0))) $numweek++;
			if (array_key_exists($i,$publicArray) && ((date("w",$i) != 6) && (date("w",$i) != 0))) $numweek++;
			
			if (array_key_exists($i-86400,$publicArray) && (date("w",$i) == 1))
				if (array_key_exists($i,$publicArray)) {}
				else $numweek++;
		}
        $diff = $endDate - $startDate;
		$days = ($diff / 86400) - $numweek + 1;
		return $days;
    }
	
	public function day(Request $request)
    {
		//Validation
        $validator = Validator::make($request->all(), [
            'hr_person_id' => 'required',
            'leave_type' => 'required',
            'day_requested' => 'required',
            'application_type' => 'required',
            'day' => 'required',
        ]);
        $validator->after(function ($validator) use($request) {
            $hrPersonId = $request->input('hr_person_id');
            $leaveType = $request->input('leave_type');
            $dayRequested = $request->input('day_requested');
            $applicationType = $request->input('application_type');
            $availableBalance = 0;
			$extraDays = 0;
			//make sure application doesnot overlaps
			$day = $request->input('day');
			$dates = explode(' - ', $day);
			$startDate = str_replace('/', '-', $dates[0]);
			$startDate = strtotime($startDate);
			$endDate = str_replace('/', '-', $dates[1]);
			$endDate = strtotime($endDate);
			$previousApplication = leave_application::select('id')
				->where('hr_id', $hrPersonId)
				//->where('leave_type_id', $leaveType)
				->whereIn('status', [1, 2, 3,4,5])
				->where(function($query) use ($startDate, $endDate){
                  $query->wherebetween('start_date', [$startDate,$endDate])
                        ->orwherebetween('end_date', [$startDate,$endDate]);
                })
				->first();
			if (!empty($previousApplication))
				$validator->errors()->add('day_requested', "Sorry!!! Your applicatiion cannot be processed you already have an applicatiion overlaping.");
			if (!empty($hrPersonId) && !empty($leaveType))
			{
				$balance = DB::table('leave_credit')
				->select('leave_balance')
				->where('hr_id', $hrPersonId)
				->where('leave_type_id', $leaveType)
				->first();
				$row = leave_configuration::first();
				$numberAnnual = !empty($row->allow_annual_negative_days) ? $row->allow_annual_negative_days : 0;
				$numberSick = !empty($row->allow_sick_negative_days) ? $row->allow_sick_negative_days : 0;
				if ($leaveType == 1) $extraDays = $numberAnnual;
				elseif ($leaveType == 5) $extraDays = $numberSick;
				$availableBalance = !empty($balance->leave_balance) ? $balance->leave_balance / 8: 0;
				$availableBalance = $availableBalance  + $extraDays;
				if (!empty($availableBalance))
				{
					if ($applicationType == 1)
					{
						if ($dayRequested > $availableBalance)
							$validator->errors()->add('day_requested', "Sorry!!! Your Application cannot be processed, you only have $availableBalance day(s), and applied for $dayRequested day(s).");
					}
					else
						$validator->errors()->add('day_requested', "Sorry!!! You cannot make an hour aplication here.");
				}
				else 
					$validator->errors()->add('day_requested', "Sorry!!! you do not have leave days available to perform this action.");
			}
			else
			{
				$validator->errors()->add('hr_person_id', "Please Select an employee.");
				$validator->errors()->add('leave_type', "Please Select a leave type.");
			}
			// check if the employee report to someone.
			$managerDetails = HRPerson::where('id', $hrPersonId)
            ->select('manager_id')->first();
			if (empty($managerDetails['manager_id']))
				$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the employee selected does not have a manager. please go to the employee profile and assign one.");
			// check there is document if leave is family, sick and study leave.
			if ($leaveType == 2 || $leaveType == 5  || $leaveType == 6)
			{
				if ($leaveType == 2) $leaveName = 'family leave';
				elseif ($leaveType == 5) $leaveName = 'sick leave';
				else $leaveName = 'study leave';
				if (!$request->hasFile('supporting_docs'))
					$validator->errors()->add('supporting_docs', "Sorry!!! Your application cannot be completed, this $leaveName applicatiion required a supporting document to be uploaded.");
			}
        });
        if ($validator->fails()) {
            return redirect("/leave/application")
                ->withErrors($validator)
                ->withInput();
        }
        $leaveApp = $request->all();
        unset($leaveApp['_token']);
		$day = $leaveApp['day'];
        $dates = explode(' - ', $day);
        $startDate = str_replace('/', '-', $dates[0]);
        $startDate = strtotime($startDate);
        $endDate = str_replace('/', '-', $dates[1]);
        $endDate = strtotime($endDate);
        $ApplicationDetails = array();
        $status = array();
        $hrID = $leaveApp['hr_person_id'];
        $typID = $leaveApp['leave_type'];
        $dayRequested = $leaveApp['day_requested'] * 8;
        $managerDetails = HRPerson::where('id', $hrID)
            ->select('manager_id')->first();
        $managerID = !empty($managerDetails['manager_id']) ? $managerDetails['manager_id'] : 0;
        $Details = leave_credit::where('hr_id', $hrID)
            ->where('leave_type_id', $typID)
            ->first();
        $leaveBalance = !empty($Details['leave_balance']) ? $Details['leave_balance'] : 0;
        //query the hr table based on employeeId
        $HRpeople = HRPerson::find($hrID);
        $username = $HRpeople->first_name." ".$HRpeople->surname;
        // call the function
        $ApplicationDetails = LeaveApplicationController::ApplicationDetails(0, $hrID);
        $applicatiionStaus = $ApplicationDetails['status'];
        $levtype = $request->input('leave_type');
		$levApp = new leave_application();
        $levApp->leave_type_id = $request->input('leave_type');
        $levApp->start_date = $startDate;
        $levApp->end_date = $endDate;
        $levApp->leave_taken = $dayRequested;
        $levApp->hr_id = $request->input('hr_person_id');
        $levApp->notes = $request->input('description');
        $levApp->status = $applicatiionStaus;
        $levApp->manager_id = $managerID;
        $levApp->save();
        //Upload supporting Documents
        if ($request->hasFile('supporting_docs')) {
            $fileExt = $request->file('supporting_docs')->extension();
            if (in_array($fileExt, ['doc', 'docx', 'pdf']) && $request->file('supporting_docs')->isValid()) {
                $fileName = time() . "_supporting_docs." . $fileExt; 
                $request->file('supporting_docs')->storeAs('Leave/LeaveDocuments', $fileName);
                //Update file name in hr table
                $levApp->supporting_docs = $fileName;
                $levApp->update();
            }
        }
		// get leave type value
		$leaveTypes = LeaveType::where('id', $request->input('leave_type'))->where('status', 1)->first();
        // send email to manager
		if (!empty($ApplicationDetails['email']))
			Mail::to($ApplicationDetails['email'])->send(new leave_applications($ApplicationDetails['first_name'], $leaveTypes->name, $ApplicationDetails['email'], $username));

        AuditReportsController::store('Leave Management', 'Leave day application', "Accessed By User", 0);
        #leave history audit
        LeaveHistoryAuditController::store("Leave application submitted by : $username", '', $leaveBalance, $dayRequested, $leaveBalance, $levtype, $hrID);
        return back()->with('success_application', "leave application was successful.");
    }

    public function hours(Request $request)
    {
		//Validation
        $validator = Validator::make($request->all(), [
            'hr_person_id' => 'required',
            'leave_type' => 'required',
            'date' => 'required',
            'application_type' => 'required',
            'hours' => 'required',
        ]);
        $validator->after(function ($validator) use($request) {
            $hrPersonId = $request->input('hr_person_id');
            $leaveType = $request->input('leave_type');
            $hours = $request->input('hours');
            $applicationType = $request->input('application_type');
            $availableBalance = 0;
			$extraDays = 0;
			if (!empty($hrPersonId) && !empty($leaveType))
			{
				$balance = DB::table('leave_credit')
				->select('leave_balance')
				->where('hr_id', $hrPersonId)
				->where('leave_type_id', $leaveType)
				->first();
				$row = leave_configuration::first();
				$numberAnnual = !empty($row->allow_annual_negative_days) ? $row->allow_annual_negative_days : 0;
				$numberSick = !empty($row->allow_sick_negative_days) ? $row->allow_sick_negative_days : 0;
				if ($leaveType == 1) $extraDays = $numberAnnual;
				elseif ($leaveType == 5) $extraDays = $numberSick;
				$availableBalance = !empty($balance->leave_balance) ? $balance->leave_balance : 0;
				$availableBalance = $availableBalance + ($extraDays * 8);
				if (!empty($availableBalance))
				{
					if ($applicationType == 2)
					{
						if ($hours > $availableBalance)
							$validator->errors()->add('hours', "Sorry!!! Your Application cannot be processed, you only have $availableBalance hr(s), and applied for $dayRequested hr(s).");
					}
					else
						$validator->errors()->add('hours', "Sorry!!! You cannot make an hour aplication here.");
				}
				else 
					$validator->errors()->add('hours', "Sorry you do not have leave days available to perform this action.");
			}
			else
			{
				$validator->errors()->add('hr_person_id', "Please Select an employee.");
				$validator->errors()->add('leave_type', "Please Select a leave type.");
			}
			$managerDetails = HRPerson::where('id', $hrPersonId)
            ->select('manager_id')->first();
			if (empty($managerDetails['manager_id']))
				$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the employee you Selected does not have a manager. please go to the employee profile and assign one.");
        
        });
        if ($validator->fails()) {
            return redirect("/leave/application")
                ->withErrors($validator)
                ->withInput();
        }
        $leaveApp = $request->all();
		unset($leaveApp['_token']);
        $hrID = $leaveApp['hr_person_id'];
        $hours = $leaveApp['hours'];
        $ApplicationDetails = array();
        $date = strtotime($leaveApp['date']);
        $hrID = $request->input('hr_person_id');
		$HRpeople = HRPerson::find($hrID);
        $username = $HRpeople->first_name." ".$HRpeople->surname;
        $managerDetails = HRPerson::where('id', $hrID)
            ->select('manager_id')->get()->first();
        $managerID = $managerDetails['manager_id'];
        $ApplicationDetails = LeaveApplicationController::ApplicationDetails(0, $hrID);
        $applicatiionStaus = $ApplicationDetails['status'];
        $employees = $request->input('hr_person_id');
        $typID = $request->input('leave_type');
        $HRpeople = HRPerson::find($employees);
        $username = $HRpeople->first_name;
        $Details = leave_credit::where('hr_id', $employees)
            ->where('leave_type_id', $typID)
            ->first();
        $leave_balance = $Details['leave_balance'];
        #
		$levApp = new leave_application();
        $levApp->leave_type_id = $typID;
        $levApp->hr_id = $employees;
        $levApp->notes = $request->input('description');
        $levApp->status = $applicatiionStaus;
        $levApp->start_date = $date;
        $levApp->end_date = $date;
        $levApp->leave_taken = $hours;
        $levApp->manager_id = $managerID;
        $levApp->save();
		//Upload supporting Documents
        if ($request->hasFile('supporting_docs')) {
            $fileExt = $request->file('supporting_docs')->extension();
            if (in_array($fileExt, ['doc', 'docx', 'pdf']) && $request->file('supporting_docs')->isValid()) {
                $fileName = time() . "_supporting_docs." . $fileExt;
                $request->file('supporting_docs')->storeAs('Leave/LeaveDocuments', $fileName);
                //Update file name in hr table
                $levApp->supporting_docs = $fileName;
                $levApp->update();
            }
        }
        // get leave type value
		$leaveTypes = LeaveType::where('id', $request->input('leave_type'))->where('status', 1)->first();
        // send email to manager
		if (!empty($ApplicationDetails['email']))
			Mail::to($ApplicationDetails['email'])->send(new leave_applications($ApplicationDetails['first_name'], $leaveTypes->name, $ApplicationDetails['email'], $username));

		#$action='',$descriptionAction ='',$previousBalance='',$transcation='' ,$currentBalance ='',$leave_type ='')
        AuditReportsController::store('Leave Management', 'Leave hours application ', "Accessed By User", 0);
        LeaveHistoryAuditController::store("Hours leave application submitted by : $username", 0, $leave_balance, 0, $leave_balance, $typID, $employees);
        return back()->with('success_application', "leave application was successful.");
    }

    public function viewApplication(leave_application $leave)
    {
		if (!empty($leave)) $leave = $leave->load('person','manager','leavetpe');
		
		AuditReportsController::store('Leave Management', 'Leave Application Printed', "Accessed By User");
		$companyDetails = CompanyIdentity::systemSettings();
		$companyName = $companyDetails['company_name'];
		$user = Auth::user()->load('person');

		$data['support_email'] = $companyDetails['support_email'];
		$data['company_name'] = $companyName;
		$data['full_company_name'] = $companyDetails['full_company_name'];
		$data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
		$data['date'] = date("d-m-Y");
		$data['user'] = $user;
		$data['leave'] = $leave;
		$data['file_name'] = 'LeaveApplication';
		$view = view('leave.leave_application', $data)->render();
		$pdf = resolve('dompdf.wrapper');
		$pdf->getDomPDF()->set_option('enable_html5_parser', true);
		$pdf->loadHTML($view);
		return $pdf->output();
    }
	
	//Function to accept leave applications
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
		//update leave applicatiion status
		if ($leaveId->status == 2 && $DepHead == 1) 
		{
			$leaveId->status = 3;
			$leaveId->update();
        }
		elseif ($leaveId->status == 2 && $ManHed == 1)
		{
			// update leave applicatiion status
			$leaveId->status = 1;
			$leaveId->update();
			// #Query the the leave_config days for value
			$credit = leave_credit::where('hr_id', $leaveId->hr_id)
				->where('leave_type_id', $leaveId->leave_type_id)
				->first();
			$leaveBalance = $credit->leave_balance;
			
			#subract current balance from the one applied for
			$newBalance = $leaveBalance - $daysApplied;
			$credit->leave_balance = $newBalance;
			$credit->update();
			$leaveAttachment = $this->viewApplication($leaveId);
			#send email to the user informing that the leave has been accepted
			if (!empty($hrDetails->email))
				Mail::to($hrDetails->email)->send(new Accept_application($hrDetails->first_name, $leaveAttachment));

			// send emal to Hr manager
			if (!empty($hrDetails->division_level_5))
			{
				$Dept = DivisionLevelFive::where('id', $hrDetails->division_level_5)->first();
				if (!empty($Dept->manager_id))
				{
					$deptDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
						->select('first_name', 'email')
						->first();
					if (!empty($deptDetails->email))
						Mail::to($deptDetails->email)->send(new SendLeaveApplicationToHrManager($deptDetails->first_name, $leaveAttachment));
				}
			}
			// update leave history
			LeaveHistoryAuditController::store("leave application Approved", '', $leaveBalance, $daysApplied, $newBalance, $leaveId->leave_type_id, $leaveId->hr_id);
        }
		
		AuditReportsController::store('Leave Management', 'leave_approval Informations accepted', "Edited by User: $leaveId->hr_id", 0);
        return back()->with('success_application', "leave application was successful.");
    }

    public function reject(Request $request, leave_application $levReject)
    {
        $this->validate($request, [
			'description' => 'required',
        ]);
        $leaveData = $request->all();
        unset($leaveData['_token']);

        $usedetails = HRPerson::where('id', $levReject->hr_id)
            ->select('first_name', 'surname', 'email', 'manager_id')
            ->first();
        $firstname = $usedetails['first_name'];
        $surname = $usedetails['surname'];
        $email = $usedetails['email'];
        $manager_id = !empty($usedetails['manager_id']) ? $usedetails['manager_id']: 0;

        $levReject->reject_reason = $request->input('description');
		$levReject->status = 6;
        $levReject->update();
        #send rejection email
		Mail::to($email)->send(new LeaveRejection($firstname, $surname, $email));
        AuditReportsController::store('Leave Management: ', 'leave rejected', "By User", 0);
        LeaveHistoryAuditController::store("leave application Rejected", 0, 0, 0, 0, $levReject->leave_type_id, $manager_id);
        return response()->json();
    }
	// view leave cancellation page_description
	// cancel approved applicatiion
	public function leaveSearch()
    {
		
		$employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
		$leaveTypes = LeaveType::where('status', 1)->orderBy('name', 'asc')->get();
		
		$data['leaveTypes'] = $leaveTypes;
        $data['employees'] = $employees;
		
		$data['page_title'] = "leave Management";
        $data['page_description'] = "Leave Application Cancellation";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => '/leave/search', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Leave Application', 'active' => 1, 'is_module' => 0]
        ];
		$data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Search';

        AuditReportsController::store('Leave Management', 'Leave Type Page Accessed', "Accessed By User", 0);
        return view('leave.cancellation')->with($data);   
    }
	// cancel approved applicatiion
	public function leaveSearchResults(Request $request, leave_application $leaveApplication)
    {
		$this->validate($request, [
        ]);
        $request = $request->all();
        unset($request['_token']);

        $actionFrom = $actionTo = 0;
        $hr_person_id = $request['hr_person_id'];
        $managerId = $request['manager_id'];
        $LevTypID = !empty($request['leave_type']) ? $request['leave_type'] : 0;
        $status = !empty($request['status']) ? $request['status'] : 0;
		$leaveStatus = array(1 => 'Approved', 2 => 'Require managers approval ', 3 => 'Require department head approval', 4 => 'Require hr approval', 5 => 'Require payroll approval', 6 => 'rejected', 7 => 'rejectd_by_department_head', 8 => 'rejectd_by_hr', 9 => 'rejectd_by_payroll', 10 => 'Cancelled');
		$actionDate = $request['date_applied'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
		$applications = DB::table('leave_application')
			->select('leave_application.*', 'hr_people.employee_number as employee_number'
			,'hr_people.first_name', 'hr_people.surname'
			,'hp.first_name as manager_first_name','hp.surname as manager_surname'
			, 'leave_types.name as leave_type_name')
			->leftJoin('hr_people', 'leave_application.hr_id', '=', 'hr_people.id')
			->leftJoin('hr_people as hp', 'leave_application.manager_id', '=', 'hp.id')
			->leftJoin('leave_types', 'leave_application.leave_type_id', '=', 'leave_types.id')
			->where('hr_people.status', 1)
			->where(function ($query) use ($hr_person_id) {
				if (!empty($hr_person_id)) {
					$query->where('leave_application.hr_id', $hr_person_id);
				}
			})
			->where(function ($query) use ($managerId) {
				if (!empty($managerId)) {
					$query->where('leave_application.manager_id', $managerId);
				}
			})
			->where(function ($query) use ($status) {
				if (!empty($status)) {
					$query->where('leave_application.status', $status);
				}
			})
			->where(function ($query) use ($LevTypID) {
				if (!empty($LevTypID)) {
					$query->where('leave_application.leave_type_id', $LevTypID);
				}
			})
			->where(function ($query) use ($actionFrom, $actionTo) {
				if ($actionFrom > 0 && $actionTo > 0) {
					$query->whereBetween('start_date', [$actionFrom, $actionTo]);
				}
			})
			->orderBy('hr_people.first_name')
			->orderBy('hr_people.surname')
			->orderBy('leave_types.name')
			->orderBy('leave_application.id')
			->get();
		
		$data['leaveStatus'] = $leaveStatus;
		$data['applications'] = $applications;
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Search';
		$data['page_title'] = "leave Management";
        $data['page_description'] = "Leave Cancellation";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => 'leave/search', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'leave Approval', 'active' => 1, 'is_module' => 0]
        ];
        AuditReportsController::store('Leave Management', 'Leave Approval Page Accessed', "Accessed By User", 0);
        return view('leave.leave_cancel')->with($data);
    }
	// View Leave Application
	public function viewLeaveApplication(leave_application $leave)
    {
		if (!empty($leave)) $leave = $leave->load('person','manager','leavetpe','canceller');
		AuditReportsController::store('Leave Management', 'Leave Application Printed', "Accessed By User");
		$leaveStatus = array(1 => 'Approved', 2 => 'Require managers approval ', 3 => 'Require department head approval', 4 => 'Require hr approval', 5 => 'Require payroll approval', 6 => 'rejected', 7 => 'rejectd_by_department_head', 8 => 'rejectd_by_hr', 9 => 'rejectd_by_payroll', 10 => 'Cancelled');
		//
		$currentUserID = Auth::user()->id;
		$userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'leave')->where('security_modules_access.access_level', '>', 4)
            ->where('security_modules_access.user_id', $currentUserID)->pluck('user_id')->first();  
		if (!empty($userAccess)) $isAdmin = 1;
		else $isAdmin = 0;
		
		$data['leave'] = $leave;
		$data['leaveStatus'] = $leaveStatus;
		$data['isAdmin'] = $isAdmin;
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Search';
		$data['page_title'] = "leave Management";
        $data['page_description'] = "View Application";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => 'leave/search', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'leave Approval', 'active' => 1, 'is_module' => 0]
        ];
		return view('leave.view_application')->with($data);
    }
	//
	public function cancelApplicationAdmin(Request $request, leave_application $leave)
    {
        if ($leave && in_array($leave->status, [1,2, 3, 4, 5])) {
            $this->validate($request, [
                'reason' => 'required'
            ]);
			
            $user = Auth::user()->load('person');
			
			if ($leave->status == 1)
			{
				// get leave creadit
				$credit = leave_credit::where('hr_id',$leave->hr_id)->where('leave_type_id',$leave->leave_type_id)->first(); 
				$leaveBalance = !empty($credit->leave_balance) ? $credit->leave_balance: 0;
				// update leave balance
				$credit->leave_balance = $leaveBalance + $leave->leave_taken;
				$credit->update();
				// insert into leave history
				$levHist = new leave_history();
				$levHist->hr_id = $user->person->id;
				$levHist->action_date = time();
				$levHist->description_action = "Leave application canceled and credit been updated";
				$levHist->previous_balance = $leaveBalance;
				$levHist->leave_type_id = $leave->leave_type_id;
				$levHist->transcation = $leave->leave_taken;
				$levHist->current_balance = $credit->leave_balance;
				$levHist->added_by = $user->person->id;
				$levHist->save();
			}
			// update leave applicatiion
            $leave->status = 10;
            $leave->canceller_id = $user->person->id;
            $leave->cancellation_reason = $request->input('reason');
            $leave->update();
            return response()->json(['success' => 'Leave application successfully cancelled.'], 200);
        }
    }
}