<?php

namespace App\Http\Controllers;

use App\DivisionLevelTwo;
use App\HRPerson;
use App\CompanyIdentity;
use App\Http\Requests;
use App\DivisionLevelFive;
use App\DivisionLevelFour;
use App\leave_application;
use App\leave_configuration;
use App\leave_credit;
use App\leave_custom;
use App\leave_history;
use App\LeaveType;
use App\LeaveNotifications;
use App\Mail\Accept_application;
use App\Mail\SendLeaveApplicationToManager;
use App\Mail\SendLeaveApplicationToHrManager;
use App\Mail\leave_applications;
use App\Mail\LeaveRejection;
use App\Traits\uploadFilesTrait;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;
use Throwable;

class LeaveApplicationController extends Controller
{
    use uploadFilesTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $leave_customs = leave_custom::orderBy(
            'hr_id', 'asc'
        )->get();

        if (!empty($leave_customs))
            $leave_customs = $leave_customs->load('userCustom');


        $hrID = Auth::user()->id;
        $currentUser = Auth::user()->person->id;
        $userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'leave')->where('security_modules_access.access_level', '>', 3)
            ->where('security_modules_access.user_id', $hrID)->pluck('user_id')->first();
        if (!empty($userAccess))
            $employees = HRPerson::where('status', 1)
                ->orderBy('first_name', 'asc')
                ->orderBy('surname', 'asc')
                ->get();
        else {
            $reportsTo = HRPerson::where('status', 1)
                ->where(
                    [
                        'manager_id' => $currentUser
                    ])
                ->orwhere('id', $currentUser)
                ->orderBy('first_name', 'asc')
                ->orderBy('surname', 'asc')
                ->get();


            if (count($reportsTo) > 0)
                $employees = $reportsTo;
            else
                $employees = HRPerson::where(['status' => 1, 'id' => $currentUser])->get();

        }
        $leaveTypes = LeaveType::where('status', 1)
            ->orderBy('name', 'asc')
            ->get()->load(
                ['leave_profle' => function ($query) {
                }]);
        #Query to get negative annual leave days for user based on userID and LeaveID
        $negativeannualDays = leave_configuration::select('allow_annual_negative_days')
            ->where(
                ['id' => 1,
                    'allow_annualLeave_credit' => 1
                ])->get();

        $negannualDays = !empty($negativeannualDays
            ->first()
            ->allow_annual_negative_days) ? $negativeannualDays
            ->first()
            ->allow_annual_negative_days : 0;

        #Query to get negative sick leave days for user based on userID and LeaveID
        $negativesickDays = leave_configuration::select(
            'allow_sick_negative_days')
            ->where(
                ['id' => 1,
                    'allow_sickLeave_credit' => 1
                ])->get();

        $negsickDays = !empty(
        $negativesickDays
            ->first()
            ->allow_sick_negative_days) ? $negativesickDays
            ->first()
            ->allow_sick_negative_days : 0;

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

        AuditReportsController::store(
            'Leave Management',
            'Leave Type Page Accessed',
            "Accessed By User",
            0
        );
        return view('leave.application')->with($data);
    }

    public function getAllSubordinates($users, $managerID)
    {
        $employees = HRPerson::where(
            [
                'status' => 1,
                'manager_id' => $managerID
            ])->pluck('id');

        foreach ($employees as $employee) {
            if (array_key_exists($employee, $users)) continue;
            if ($employee == $managerID) continue;
            $users[] = $employee;
            $users = LeaveApplicationController::getAllSubOrdinates($users, $employee);
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
        $subordinates = LeaveApplicationController::getAllSubordinates(array(), $loggedInEmplID);

        $people = DB::table('hr_people')->orderBy('id', 'asc')->get();
        $leaveTypes = LeaveType::where('status', 1)->get()->load(
            [
                'leave_profle' => function ($query) {
                    $query->orderBy('name', 'asc');
                }]
        );
        // left join between leaveApplication & HRPerson & LeaveType

        $leaveStatus = array(
            1 => 'Approved',
            2 => 'Require managers approval ',
            3 => 'Require department head approval',
            4 => 'Require hr approval',
            5 => 'Require payroll approval',
            6 => 'rejected',
            7 => 'rejectd_by_department_head',
            8 => 'rejectd_by_hr',
            9 => 'rejectd_by_payroll',
            10 => 'Cancelled'
        );

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

        AuditReportsController::store(
            'Leave Management',
            'Leave Approval Page Accessed',
            "Accessed By User",
            0
        );
        return view('leave.leave_approval')->with($data);
    }

    public function cancelApplication(Request $request, leave_application $leaveApplication)
    {
        if ($leaveApplication && in_array(
                $leaveApplication->status,
                [
                    2, 3, 4, 5
                ]
            )) {
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

    /**
     * @param $status
     * @return string|string[]
     */
    public static function status($status = 0)
    {

        $leaveStatusNames = [
            1 => 'Approved',
            2 => 'Require managers approval ',
            3 => 'Require department head approval',
            4 => 'Require HR approval',
            5 => 'Require payroll approval',
            6 => 'Rejected',
            7 => 'Rejected by department head',
            8 => 'Rejected by HR',
            9 => 'Rejected by payroll',
            10 => 'Cancelled'
        ];
        if ($status && $status > 0) return $leaveStatusNames[$status];
        else return $leaveStatusNames;
    }

    /**
     * @param $status
     * @param $hrID
     * @param $startDate
     * @param $endDate
     * @return array|void
     */
    public static function ApplicationDetails($status = 0, $hrID)
    {

        $date = Carbon::now(); // or whatever you're using to set it
        $start = $date->copy()->startOfDay();
        $end = $date->copy()->endOfDay();

        $startDate = strtotime($start);
        $endDate = strtotime($end);
		$details = array();
        // $startDate =
        // query the leave congif table and bring back the values
        $approvals = DB::table('leave_configuration')
            ->select('require_managers_approval',
                'require_department_head_approval',
                'require_hr_approval',
                'require_payroll_approval'
            )->first();
        // query the hr pesron  model and bring back the values of the managerg

        $hrDetails = HRPerson::where(['id' => $hrID,'status' => 1])->first();

        if ($approvals->require_managers_approval == 1)
		{
            #check if the manager is on leave or not
            $isOnleave = leave_application::where('hr_id', $hrDetails['manager_id'])
                ->where('status', '<', 2)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->wherebetween('start_date', [$startDate, $endDate])
                        ->orwherebetween('end_date', [$startDate, $endDate]);
                })->first();

            /**
             * if the manager is on leave the second in charge will have toa approve the application
             */
            if (isset($isOnleave)) 
			{
                #get the second in charge
                $managerDetails = HRPerson::getManagerDetails($hrDetails['second_manager_id']);
            } 
			else
			{
                // query the hrperson  model and bring back the values of the manager
                $managerDetails = HRPerson::getManagerDetails($hrDetails['manager_id']);
			}
			$firstName = !empty($managerDetails['first_name']) ?$managerDetails['first_name'] : '';
			$surname = !empty($managerDetails['surname']) ? $managerDetails['surname'] : '';
			$email = !empty($managerDetails['email']) ? $managerDetails['email'] : '';
			$manager_id = !empty($managerDetails['manager_id']) ? $managerDetails['manager_id'] : 0;

            // array to store manager details
            $details = array(
                'status' => 2,
                'first_name' => $firstName,
                'surname' => $surname,
                'email' => $email,
                'manager_id' => $manager_id
            );

        } 
		if ($approvals->require_department_head_approval == 1 && empty($approvals->require_managers_approval)) 
		{
            # code...  DivisionLevelFour
            // query the hrperon  model and bring back the values of the manager
            $Dept = DivisionLevelFour::where('id', $hrDetails->division_level_4)->first();
            $deptDetails = $Dept->load('manager');

            // array to store manager details
            $details = array('status' => 3, 'first_name' => $deptDetails->manager->first_name, 'surname' => $deptDetails->manager->surname, 'email' => $deptDetails->manager->email, 'manager id' => $deptDetails->manager->id);

        }
		if ($approvals->require_hr_approval == 1 && empty($approvals->require_department_head_approval) && empty($approvals->require_managers_approval)) 
		{
            # code...  hr

            // query the hrperon  model and bring back the values of the manager
            $div = DivisionLevelFive::where('id', $hrDetails->division_level_5)->first();
            $hrDetails = $div->load('hrManager');
			//echo $hrDetails->hrManager->first_name;
			//die('heheb');
            // array to store manager details
            $details = array('status' => 4, 'first_name' => $hrDetails->hrManager->first_name, 'surname' => $hrDetails->hrManager->surname, 'email' => $hrDetails->hrManager->email, 'manager id' => $hrDetails->hrManager->id);

        } 
		if ($approvals->require_payroll_approval == 1 && empty($approvals->require_hr_approval) && empty($approvals->require_department_head_approval) && empty($approvals->require_managers_approval)) {
            # code...  payroll

             // query the hrperon  model and bring back the values of the manager
            $div = DivisionLevelFive::where('id', $hrDetails->division_level_5)->first();
            $payrollDetails = $div->load('payrollOfficer');

            // array to store manager details
            $details = array('status' => 5, 'first_name' => $payrollDetails->payrollOfficer->first_name, 'surname' => $payrollDetails->payrollOfficer->surname, 'email' => $payrollDetails->payrollOfficer->email, 'manager id' => $payrollDetails->payrollOfficer->id);

        } #code here .. Require Hr
        return $details;
    }

    #function to get available days for user based on userID and LeaveID

    /**
     * @param $hrID
     * @param $typID
     * @return float|int
     */
    public function availableDays($hrID, $typID)
    {
        $row = leave_configuration::first();
        $numberAnnual = !empty($row->allow_annual_negative_days) ? $row->allow_annual_negative_days : 0;
        $numberSick = !empty($row->allow_sick_negative_days) ? $row->allow_sick_negative_days : 0;
        $extraDays = 0;
        $balance = DB::table('leave_credit')
            ->select('leave_balance')
            ->where([
                'hr_id' => $hrID,
                'leave_type_id' => $typID
            ])->get();

        if ($typID == 1) $extraDays = $numberAnnual;
        elseif ($typID == 5) $extraDays = $numberSick;
        $leaveDays = !empty($balance->first()->leave_balance) ? $balance->first()->leave_balance / 8 : 0;
        return $leaveDays /*+ $extraDays*/ ;
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return float|int
     */
    public static function calculatedays($dateFrom, $dateTo)
    {
        //convert dates
        $startDate = strtotime($dateFrom);
        $endDate = strtotime($dateTo);
        $onceOffHoliday = date("Y", $startDate);
        // calculate public holidays and weekends
        $numweek = 0;
        $publicHolidays = array();
        $publicHolidays = DB::table('public_holidays')
            ->where(function ($query) use ($onceOffHoliday) {
                $query->whereNull('year')
                    ->orWhere('year', '=', 0)
                    ->orWhere('year', '=', $onceOffHoliday);
            })
            ->pluck('day');
        # Add Easter Weekend to list of public holidays
        $easterSunday = easter_date(date("Y", $endDate));
        $publicHolidays[] = $easterSunday - (2 * 3600 * 24);
        $publicHolidays[] = $easterSunday + (3600 * 24);

        for ($i = $startDate; $i <= $endDate; $i = $i + 86400) {
            $publicArray = array();
            foreach ($publicHolidays as $key => $value) {
                $day = date("Y", $i) . "-" . date("m", $value) . "-" . date("d", $value);
                $day = strtotime($day);
                $publicArray[$day] = 0;
            }
            if (((date("w", $i) == 6) || (date("w", $i) == 0))) $numweek++;
            if (array_key_exists($i, $publicArray) && ((date("w", $i) != 6) && (date("w", $i) != 0))) $numweek++;

            if (array_key_exists($i - 86400, $publicArray) && (date("w", $i) == 1))
                if (array_key_exists($i, $publicArray)) {
                } else $numweek++;
        }
        $diff = $endDate - $startDate;
        $days = ($diff / 86400) - $numweek + 1;
        return $days;
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function day(Request $request)
    {
        //Validation

        $validator = Validator::make($request->all(), [
            'hr_person_id' => 'numeric|min:1',
            'leave_type' => 'numeric|min:1',
            'day_requested' => 'numeric|min:1',
            'application_type' => 'numeric|min:1',
            'day' => 'required',
			'description' => 'nullable|string|max:255', // applies validation only if 'name' is present and not null
        ]);
		
        $validator->after(function ($validator) use ($request) {
            $hrPersonId = $request->input('hr_person_id');
            $leaveType = $request->input('leave_type');
            $dayRequested = $request->input('day_requested');
            $applicationType = $request->input('application_type');
            $availableBalance = 0;
            $extraDays = 0;
			if (!empty($hrPersonId) && !empty($leaveType)  && !empty($dayRequested))
			{
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
					->whereIn('status', [1, 2, 3, 4, 5])
					->where(function ($query) use ($startDate, $endDate) {
						$query->wherebetween('start_date', [$startDate, $endDate])
							->orwherebetween('end_date', [$startDate, $endDate]);
					})
					->first();
				if (!empty($previousApplication))
					$validator->errors()->add('day_requested', "Sorry!!! Your application cannot be processed you already have an application overlaping.");
					// check user have available days
					$balance = DB::table('leave_credit')
						->select('leave_balance')
						->where('hr_id', $hrPersonId)
						->where('leave_type_id', $leaveType)
						->first();
					
					$row = leave_configuration::first();
					$require_managers_approval = !empty($row->require_managers_approval) ? (int) $row->require_managers_approval : 0;
					$require_department_head_approval = !empty($row->require_department_head_approval) ? (int) $row->require_department_head_approval : 0;
					$require_hr_approval = !empty($row->require_hr_approval) ? (int) $row->require_hr_approval : 0;
					$require_payroll_approval = !empty($row->require_payroll_approval) ? (int) $row->require_payroll_approval : 0;
					$numberAnnual = !empty($row->allow_annual_negative_days) ? $row->allow_annual_negative_days : 0;
					$numberSick = !empty($row->allow_sick_negative_days) ? $row->allow_sick_negative_days : 0;
					$allowUnpaid = !empty($row->allow_unpaid_leave_when_annual_done) ? $row->allow_unpaid_leave_when_annual_done : 0;
					if (!empty($allowUnpaid))
					{
						$availableBalance = !empty($balance->leave_balance) ? $balance->leave_balance / 8 : 0;
						if ($leaveType == 1 && $availableBalance < 0)
							$request->merge(['leave_type' => 7]);
						
					}
					else
					{
						if ($leaveType == 1) $extraDays = $numberAnnual;
						elseif ($leaveType == 5) $extraDays = $numberSick;
						$availableBalance = !empty($balance->leave_balance) ? $balance->leave_balance / 8 : 0;
						$availableBalance = $availableBalance + $extraDays;
						if (!empty($availableBalance)) 
						{
							if ($applicationType == 1) 
							{
								if ($dayRequested > $availableBalance)
									$validator->errors()->add('day_requested', "Sorry!!! Your Application cannot be processed, you only have $availableBalance day(s), and applied for $dayRequested day(s).");
							}
							else
								$validator->errors()->add('day_requested', "Sorry!!! You cannot make an hour application here.");
						}
						else
							$validator->errors()->add('day_requested', "Sorry!!! you do not have leave days available to perform this action.");
					}
					

				#will do changes here
				// check if manager is on leave
				// check if the employee report to someone.
				if ($require_managers_approval == 1)
				{
					$managerDetails = HRPerson::where('id', $hrPersonId)->select('manager_id')->first();
					if (empty($managerDetails['manager_id']))
						$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the employee selected does not have a manager. please go to the employee profile and assign one.");
					$secondManagerDetails = HRPerson::where('id', $hrPersonId)
						->select('second_manager_id')->first();
					if (empty($secondManagerDetails['second_manager_id']))
						$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the employee selected does not have a second manager. please go to the employee profile and assign one.");
				}
				// check department head is set
				if ($require_department_head_approval == 1)
				{
					// check employee have a dept assigned
					$depDetails = HRPerson::where('id', $hrPersonId)->select('division_level_4')->first();
					if (empty($depDetails->division_level_4))
						$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the employee selected does not have a department assigned. please go to the employee profile and assign one.");
					else
					{
						$Dept = DivisionLevelFour::where('id', $depDetails->division_level_4)->first();
						if (empty($Dept['manager_id']))
							$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the department the employee belongs to, does not have a manager assigned. please go to the department settings to assign one.");
					}
					
				}
				// check hr is set
				if ($require_hr_approval == 1)
				{
					// check employee have a dept assigned
					$hrDetails = HRPerson::where('id', $hrPersonId)->select('division_level_5')->first();
					if (empty($hrDetails->division_level_5))
						$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the employee selected does not have a division assigned. please go to the employee profile and assign one.");
					else
					{
						$div = DivisionLevelFive::where('id', $hrDetails->division_level_5)->first();
						if (empty($div['hr_manager_id']))
							$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the division the employee belongs to, does not have an hr manager assigned. please go to the division settings to assign one.");
					}
					
				}
				// check payroll is set
				if ($require_payroll_approval == 1)
				{
					// check employee have a div assigned
					$hrDetails = HRPerson::where('id', $hrPersonId)->select('division_level_5')->first();
					if (empty($hrDetails->division_level_5))
						$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the employee selected does not have a division assigned. please go to the employee profile and assign one.");
					else
					{
						$div = DivisionLevelFive::where('id', $hrDetails->division_level_5)->first();
						if (empty($div['payroll_officer']))
							$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the division the employee belongs to, does not have an payroll officer assigned. please go to the division settings to assign one.");
					}
					
				}
				// check there is document if leave is family, sick and study leave.
				if ($leaveType == 2 || $leaveType == 5 || $leaveType == 6) {
					if ($leaveType == 2) $leaveName = 'family leave';
					elseif ($leaveType == 5) $leaveName = 'sick leave';
					else $leaveName = 'study leave';
					if (!$request->hasFile('supporting_docs'))
						$validator->errors()->add('supporting_docs', "Sorry!!! Your application cannot be completed, this $leaveName applicatiion required a supporting document to be uploaded.");
				}
			}
			else 
			{
                $validator->errors()->add('hr_person_id', "Please make sure an employee is selected.");
                $validator->errors()->add('leave_type', "Please make sure a leave type is selected.");
                $validator->errors()->add('day_requested', "Please make sure days requested is showing.");
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
		$changeLeave = $this->changeLeaveType($request);
        $typID = !empty($changeLeave) ? $changeLeave : $leaveApp['leave_type'];

        $dayRequested = $leaveApp['day_requested'] * 8;
        //get manager details
        $managerDetails = HRPerson::getManagerDetails($hrID);

        $managerID = !empty($managerDetails['manager_id']) ? $managerDetails['manager_id'] : 0;

        $Details = leave_credit::getLeaveCredit($hrID, $typID);

        $leaveBalance = !empty($Details['leave_balance']) ? $Details['leave_balance'] : 0;
        //query the hr table based on employeeId

        $fullname = $managerDetails->first_name . " " . $managerDetails->surname;

        // call the function
        $ApplicationDetails = $this->ApplicationDetails(0, $hrID);

        //LeaveApplicationController::ApplicationDetails(0, $hrID, $startDate, $endDate);

        $applicationStatus = $ApplicationDetails['status'];

        // save details into leave application
        $this->persistLeaveApplicationDetails(
            $request,
            $startDate,
            $endDate,
            $dayRequested,
            $applicationStatus,
            $managerID
        );

        Alert::toast('Application was Successfully ', 'success');
        // get leave type value
        $leaveTypes = LeaveType::getAllLeaveTypes($request['leave_type']);

        // send email to manager
        if (!empty($ApplicationDetails['email']))
            Mail::to(
                $ApplicationDetails['email'])->send(new leave_applications(
                $ApplicationDetails['first_name'],
                $ApplicationDetails['email'],
                $leaveTypes->name,
                $fullname
            ));

        /**
         * Global; audit
         */
        AuditReportsController::store(
            'Leave Management',
            'Leave day application',
            "Accessed By User",
            0
        );

        /**
         * leave history audit
         */
        LeaveHistoryAuditController::store(
            "Leave application submitted by : $fullname",
            'Leave application for day',
            $leaveBalance,
            $dayRequested,
            $leaveBalance,
            $request['leave_type'],
            $hrID
        );

        return back()->with('success_application', "leave application was successful.");

    }

    /**
     * @param $request
     * @param $startDate
     * @param $endDate
     * @param $dayRequested
     * @param $applicationStatus
     * @param $managerID
     * @return void
     */
    public static function persistLeaveApplicationDetails(
        $request,
        $startDate,
        $endDate,
        $dayRequested,
        $applicationStatus,
        $managerID
    )
    {
        $levApp = leave_application::create([
            'leave_type_id' => $request['leave_type'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'leave_taken' => $dayRequested,
            'hr_id' => $request['hr_person_id'],
            'notes' => $request['description'],
            'status' => $applicationStatus,
            'manager_id' => $managerID,
        ]);

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

    }
	 /**
     * @param $request

     */
    public static function changeLeaveType($request)
    {
		$row = leave_configuration::first();
		$allowUnpaid = !empty($row->allow_unpaid_leave_when_annual_done) ? $row->allow_unpaid_leave_when_annual_done : 0;
		$leaveType = (int) $request['leave_type'];

		if (!empty($allowUnpaid) && $leaveType === 1)
		{
			$balance = DB::table('leave_credit')
			->select('leave_balance')
			->where('hr_id', $request['hr_person_id'])
			->where('leave_type_id', $leaveType)
			->first();
		
			$availableBalance = !empty($balance->leave_balance) ? $balance->leave_balance / 8 : 0;

			if ($availableBalance <=0) 
			{
				$request->merge(['leave_type' => 7]);
				return 7;
			}
			else return 0;
		}
        

    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function hours(Request $request)
    {
        //Validation
        $validator = Validator::make($request->all(), [
            'hr_person_id' => 'numeric|min:1',
            'leave_type' => 'numeric|min:1',
            'date' => 'required',
            'application_type' => 'numeric|min:1',
            'hours' => 'numeric|min:1',
			'description' => 'nullable|string|max:255', // applies validation only if 'name' is present and not null
        ]);

        $validator->after(function ($validator) use ($request) {
            $hrPersonId = $request['hr_person_id'];
            $leaveType = $request['leave_type'];
            $hours = $request['hours'];
            $applicationType = $request['application_type'];
            $availableBalance = 0;
            $extraDays = 0;

            if (!empty($hrPersonId) && !empty($leaveType)) {
				
                $balance = DB::table('leave_credit')
                    ->select('leave_balance')
                    ->where(
                        [
                            'hr_id' => $hrPersonId,
                            'leave_type_id' => $leaveType
                        ])->first();

                $row = leave_configuration::first();
				$require_managers_approval = !empty($row->require_managers_approval) ? (int) $row->require_managers_approval : 0;
				$require_department_head_approval = !empty($row->require_department_head_approval) ? (int) $row->require_department_head_approval : 0;
				$require_hr_approval = !empty($row->require_hr_approval) ? (int) $row->require_hr_approval : 0;
				$require_payroll_approval = !empty($row->require_payroll_approval) ? (int) $row->require_payroll_approval : 0;
                $numberAnnual = !empty($row->allow_annual_negative_days) ? $row->allow_annual_negative_days : 0;
                $numberSick = !empty($row->allow_sick_negative_days) ? $row->allow_sick_negative_days : 0;
				$allowUnpaid = !empty($row->allow_unpaid_leave_when_annual_done) ? $row->allow_unpaid_leave_when_annual_done : 0;
				if (!empty($allowUnpaid))
				{
					$availableBalance = !empty($balance->leave_balance) ? $balance->leave_balance / 8 : 0;
					if ($leaveType == 1 && $availableBalance < 0)
						$request->merge(['leave_type' => 7]);
					
				}
				else
				{
					if ($leaveType == 1) $extraDays = $numberAnnual;
					elseif ($leaveType == 5) $extraDays = $numberSick;
					$availableBalance = !empty($balance->leave_balance) ? $balance->leave_balance : 0;
					$availableBalance = $availableBalance + ($extraDays * 8);
					if (!empty($availableBalance)) 
					{
						if ($applicationType == 2) 
						{
							if ($hours > $availableBalance)
                            $validator->errors()->add('hours', "Sorry!!! Your Application cannot be processed, you only have $availableBalance hr(s), and applied for $hours hr(s).");
						}
						else
							$validator->errors()->add('hours', "Sorry!!! You cannot make an hour aplication here.");
					}
					else
						$validator->errors()->add('hours', "Sorry you do not have leave days available to perform this action.");
				}
				// check if the user have a manager and second manager assigned
				// check if the employee report to someone.
				if ($require_managers_approval == 1)
				{
					$managerDetails = HRPerson::where('id', $hrPersonId)->select('manager_id')->first();
					if (empty($managerDetails['manager_id']))
						$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the employee selected does not have a manager. please go to the employee profile and assign one.");
					$secondManagerDetails = HRPerson::where('id', $hrPersonId)
						->select('second_manager_id')->first();
					if (empty($secondManagerDetails['second_manager_id']))
						$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the employee selected does not have a second manager. please go to the employee profile and assign one.");
				}
				// check department head is set
				if ($require_department_head_approval == 1)
				{
					// check employee have a dept assigned
					$depDetails = HRPerson::where('id', $hrPersonId)->select('division_level_4')->first();
					if (empty($depDetails->division_level_4))
						$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the employee selected does not have a department assigned. please go to the employee profile and assign one.");
					else
					{
						$Dept = DivisionLevelFour::where('id', $depDetails->division_level_4)->first();
						if (empty($Dept['manager_id']))
							$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the department the employee belongs to, does not have a manager assigned. please go to the department settings to assign one.");
					}
					
				}
				// check hr is set
				if ($require_hr_approval == 1)
				{
					// check employee have a dept assigned
					$hrDetails = HRPerson::where('id', $hrPersonId)->select('division_level_5')->first();
					if (empty($hrDetails->division_level_5))
						$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the employee selected does not have a division assigned. please go to the employee profile and assign one.");
					else
					{
						$div = DivisionLevelFive::where('id', $hrDetails->division_level_5)->first();
						if (empty($div['hr_manager_id']))
							$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the division the employee belongs to, does not have an hr manager assigned. please go to the division settings to assign one.");
					}
					
				}
				// check payroll is set
				if ($require_payroll_approval == 1)
				{
					// check employee have a div assigned
					$hrDetails = HRPerson::where('id', $hrPersonId)->select('division_level_5')->first();
					if (empty($hrDetails->division_level_5))
						$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the employee selected does not have a division assigned. please go to the employee profile and assign one.");
					else
					{
						$div = DivisionLevelFive::where('id', $hrDetails->division_level_5)->first();
						if (empty($div['payroll_officer']))
							$validator->errors()->add('hr_person_id', "Sorry!!! Your application cannot be completed, the division the employee belongs to, does not have an payroll officer assigned. please go to the division settings to assign one.");
					}
					
				}
			}
			else 
			{
				$validator->errors()->add('hr_person_id', "Please make sure an employee is selected.");
                $validator->errors()->add('leave_type', "Please make sure a leave type is selected.");
            }
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

        $username = $HRpeople->first_name . " " . $HRpeople->surname;

        $managerDetails = HRPerson::where('id', $hrID)
            ->select('manager_id')
            ->first();

        $managerID = $managerDetails['manager_id'];

        $ApplicationDetails = LeaveApplicationController::ApplicationDetails(0, $hrID);

        $applicatiionStaus = $ApplicationDetails['status'];

        $employees = $request->input('hr_person_id');
		$changeLeave = $this->changeLeaveType($request);
        $typID = !empty($changeLeave) ? $changeLeave : $request->input('leave_type');

        $HRpeople = HRPerson::find($employees);

        $username = $HRpeople->first_name;

        $Details = leave_credit::where('hr_id', $employees)
            ->where('leave_type_id', $typID)
            ->first();

        $leave_balance = !empty($Details['leave_balance']) ? $Details['leave_balance'] : 0;
        #

        $levApp = leave_application::create([
            'leave_type_id' => $typID,
            'hr_id' => $employees,
            'notes' => $request['description'],
            'status' => $applicatiionStaus,
            'start_date' => $date,
            'end_date' => $date,
            'leave_taken' => $hours,
            'manager_id' => $managerID,
        ]);


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
        $leaveTypes = LeaveType::where(
            [
                'id' => $request['leave_type'],
                'status' => 1
            ])->first();

        // send email to manager
        if (!empty($ApplicationDetails['email']))
            Mail::to(
                $ApplicationDetails['email'])->send(
                new leave_applications(
                    $ApplicationDetails['first_name'],
                    $leaveTypes->name,
                    $ApplicationDetails['email'],
                    $username)
            );

        #$action='',$descriptionAction ='',$previousBalance='',$transcation='' ,$currentBalance ='',$leave_type ='')
        AuditReportsController::store(
            'Leave Management',
            'Leave hours application ',
            "Accessed By User",
            0
        );

        LeaveHistoryAuditController::store(
            "Hours leave application submitted by : $username",
            0,
            $leave_balance,
            0,
            $leave_balance,
            $typID,
            $employees
        );

        return back()->with('success_application', "leave application was successful.");
    }

    /**
     * @param leave_application $leave
     * @return mixed
     * @throws Throwable
     */
    public function viewApplication(leave_application $leave)
    {
        if (!empty($leave)) $leave = $leave->load('person', 'manager', 'leavetpe');

        AuditReportsController::store(
            'Leave Management',
            'Leave Application Printed',
            "Accessed By User"
        );

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
            $levReject->hr_id
        );
        return response()->json();
    }
    // view leave cancellation page_description
    // cancel approved applicatiion
    /**
     * @return Factory|Application|View
     */
    public function leaveSearch()
    {

        $employees = HRPerson::where('status', 1)
            ->orderBy('first_name', 'asc')
            ->orderBy('surname', 'asc')
            ->get();

        $leaveTypes = LeaveType::where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

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

        AuditReportsController::store(
            'Leave Management',
            'Leave Type Page Accessed',
            "Accessed By User",
            0
        );

        return view('leave.cancellation')->with($data);
    }

    /**
     * @param Request $request
     * @param leave_application $leaveApplication
     * @return Factory|Application|View
     */
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

        $leaveStatus = array(
            1 => 'Approved',
            2 => 'Require managers approval ',
            3 => 'Require department head approval',
            4 => 'Require hr approval',
            5 => 'Require payroll approval',
            6 => 'rejected',
            7 => 'rejectd_by_department_head',
            8 => 'rejectd_by_hr',
            9 => 'rejectd_by_payroll',
            10 => 'Cancelled'
        );

        $actionDate = $request['date_applied'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $applications = DB::table('leave_application')
            ->select('leave_application.*', 'hr_people.employee_number as employee_number'
                , 'hr_people.first_name', 'hr_people.surname'
                , 'hp.first_name as manager_first_name', 'hp.surname as manager_surname'
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
        $data['page_description'] = "Leave Search";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => 'leave/search', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Leave Search', 'active' => 1, 'is_module' => 0]
        ];

        AuditReportsController::store(
            'Leave Management',
            'Leave Search Page Accessed',
            "Accessed By User",
            0
        );

        return view('leave.leave_cancel')->with($data);
    }

    /**
     * @param leave_application $leave
     * @return Factory|Application|View
     */
    public function viewLeaveApplication(leave_application $leave)
    {
        if (!empty($leave)) $leave = $leave->load('person', 'manager', 'leavetpe', 'canceller');

        AuditReportsController::store(
            'Leave Management',
            'Leave Application Printed',
            "Accessed By User"
        );

        $leaveStatus = array(
            1 => 'Approved',
            2 => 'Require managers approval ',
            3 => 'Require department head approval',
            4 => 'Require hr approval',
            5 => 'Require payroll approval',
            6 => 'rejected',
            7 => 'rejectd_by_department_head',
            8 => 'rejectd_by_hr',
            9 => 'rejectd_by_payroll',
            10 => 'Cancelled'
        );

        //
        $currentUserID = Auth::user()->id;
        $userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'leave')->where('security_modules_access.access_level', '>=', 4)
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
            ['title' => 'leave view', 'active' => 1, 'is_module' => 0]
        ];
        return view('leave.view_application')->with($data);
    }

    /**
     * @param Request $request
     * @param leave_application $leave
     * @return JsonResponse|void
     */
    public function cancelApplicationAdmin(Request $request, leave_application $leave)
    {
        if ($leave && in_array($leave->status, [1, 2, 3, 4, 5])) {
            $this->validate($request, [
                'reason' => 'required'
            ]);

            $user = Auth::user()->load('person');

            if ($leave->status == 1) {
                // get leave creadit
                $credit = leave_credit::where(
                    [
                        'hr_id' => $leave->hr_id,
                        'leave_type_id' => $leave->leave_type_id
                    ])->first();
				if (!empty($credit))
				{
					$leaveBalance = !empty($credit->leave_balance) ? $credit->leave_balance : 0;
					// update leave balance
					$credit->leave_balance = $leaveBalance + $leave->leave_taken;
					$credit->update();
					
					    leave_history::create([
						'hr_id' => $leave->hr_id,
						'action_date' => time(),
						'description' => $request->input('reason'),
						'previous_balance' => $leaveBalance,
						'leave_type_id' => $leave->leave_type_id,
						'transcation' => $leave->leave_taken,
						'current_balance' => $credit->leave_balance,
						'added_by' => $user->person->id
					]);
				}
            }
            // update leave applicatiion
            $leave->status = 10;
            $leave->canceller_id = $user->person->id;
            $leave->cancellation_reason = $request->input('reason');
            $leave->update();
            return response()->json(['success' => 'Leave application successfully cancelled.'], 200);
        }
    }
	/**
     * @param Request $request
     * @param leave_application $leaveId
     * @return RedirectResponse
     * @throws Throwable
     */
    public function AcceptLeave(Request $request, leave_application $leaveId)
    {
        #query the hr person table
        $hrDetails = HRPerson::where('id', $leaveId->hr_id)->where('status', 1)->first();
        $daysApplied = $leaveId->leave_taken;
        //check leave approvals setup
        $approvals = leave_configuration::first();
		
		$require_managers_approval = !empty($approvals->require_managers_approval) ? (int) $approvals->require_managers_approval : 0;
		$require_department_head_approval = !empty($approvals->require_department_head_approval) ? (int) $approvals->require_department_head_approval : 0;
		$require_hr_approval = !empty($approvals->require_hr_approval) ? (int) $approvals->require_hr_approval : 0;
		$require_payroll_approval = !empty($approvals->require_payroll_approval) ? (int) $approvals->require_payroll_approval : 0;
		// get leave credit details
		$credit = leave_credit::getLeaveCredit($leaveId->hr_id, $leaveId->leave_type_id);
		if (!empty( $credit))
		{
			$leaveBalance = !empty($credit->leave_balance) ? $credit->leave_balance : 0;
			#subract current balance from the one applied for
			$newBalance = $leaveBalance - $daysApplied;
		}
        //update leave application status
       if ($leaveId->status == 2) // manager approving check if other approval steps are available
	   {
            // update leave application status
			$status = 1;
			if ($require_department_head_approval == 1)
			{
				$status = 3;
				$Dept = DivisionLevelFour::where('id', $hrDetails->division_level_4)->first();
				$deptDetails = $Dept->load('manager');
				$auditText = "Leave application approved by manager, awaiting department head approval";
				// array to store manager details
				$detailsArray = array('first_name' => $deptDetails->manager->first_name, 'surname' => $deptDetails->manager->surname, 'email' => $deptDetails->manager->email);
			}
            if ($require_hr_approval == 1 && empty($require_department_head_approval))
			{
				$status = 4;
				// email department head to approve
				$div = DivisionLevelFive::where('id', $hrDetails->division_level_5)->first();
				$hrDetails = $div->load('hrManager');
				$auditText = "Leave application approved by manager, awaiting HR approval";
				// array to store manager details
				$detailsArray = array('first_name' => $hrDetails->hrManager->first_name, 'surname' => $hrDetails->hrManager->surname, 'email' => $hrDetails->hrManager->email);
			}
			if ($require_payroll_approval == 1 && empty($require_hr_approval) && empty($require_department_head_approval))
			{
				$status = 5;
				// query the hrperon  model and bring back the values of the manager
				$div = DivisionLevelFive::where('id', $hrDetails->division_level_5)->first();
				$payrollDetails = $div->load('payrollOfficer');
				$auditText = "Leave application by manager, awaiting payroll officer approval";
				// array to store manager details
				$detailsArray = array('first_name' => $payrollDetails->payrollOfficer->first_name, 'surname' => $payrollDetails->payrollOfficer->surname, 'email' => $payrollDetails->payrollOfficer->email,);
			}
			
			if ($status == 1)
			{
				$auditText = "Leave application approved by manager";
				// #Query the  leave_config days for value
				if (!empty( $credit))
				{
					$credit->leave_balance = $newBalance;
					$credit->update();
				}
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
			}
			else
			{
				// get leave type value
				$leaveTypes = LeaveType::where(
					[
						'id' => $leaveId->leave_type_id,
						'status' => 1
					])->first();
					// send email to manager
				if (!empty($detailsArray['email']))
					Mail::to($detailsArray['email'])->send(
						new leave_applications($detailsArray['first_name'],$leaveTypes->name,
							$detailsArray['email'],$hrDetails->first_name." ".$hrDetails->surname));
			}          
        }
		elseif ($leaveId->status == 3)  // dept head approving check if other approval steps are available
		{
			
            $status = 1;
            if ($require_hr_approval == 1)
			{
				$status = 4;
				// email department head to approve
				$div = DivisionLevelFive::where('id', $hrDetails->division_level_5)->first();
				$hrDetails = $div->load('hrManager');
				$auditText = "Leave application approved by department head awaiting HR approval";
				// array to store manager details
				$detailsArray = array('first_name' => $hrDetails->hrManager->first_name, 'surname' => $hrDetails->hrManager->surname, 'email' => $hrDetails->hrManager->email);
			}
			if ($require_payroll_approval == 1 && empty($require_hr_approval))
			{
				$status = 5;
				// query the hrperon  model and bring back the values of the manager
				$div = DivisionLevelFive::where('id', $hrDetails->division_level_5)->first();
				$payrollDetails = $div->load('payrollOfficer');
				$auditText = "Leave application approved by department head awaiting to payroll officer";
				// array to store manager details
				$detailsArray = array('first_name' => $payrollDetails->payrollOfficer->first_name, 'surname' => $payrollDetails->payrollOfficer->surname, 'email' => $payrollDetails->payrollOfficer->email,);
			}
			
			if ($status == 1)
			{
				$auditText = "Leave application approved by department head";
				// #Query the  leave_config days for value
				if (!empty($credit))
				{
					$credit->leave_balance = $newBalance;
					$credit->update();
				}
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
			}
			else
			{
				// get leave type value
				$leaveTypes = LeaveType::where(
					[
						'id' => $leaveId->leave_type_id,
						'status' => 1
					])->first();
				// send email to manager
				if (!empty($detailsArray['email']))
					Mail::to($detailsArray['email'])->send(new leave_applications($detailsArray['first_name'],
							$leaveTypes->name,$detailsArray['email'],	$hrDetails->first_name." ".$hrDetails->surname));
			}

        }
		elseif ($leaveId->status == 4)  // hr approving check if other approval steps are available
		{
            $status = 1;
			if ($require_payroll_approval == 1)
			{
				$status = 5;
				// query the hrperon  model and bring back the values of the manager
				$div = DivisionLevelFive::where('id', $hrDetails->division_level_5)->first();
				$payrollDetails = $div->load('payrollOfficer');
				$auditText = "Leave application approved by hr awaiting payroll officer approval";
				// array to store manager details
				$detailsArray = array('first_name' => $payrollDetails->payrollOfficer->first_name, 'surname' => $payrollDetails->payrollOfficer->surname, 'email' => $payrollDetails->payrollOfficer->email,);
			}
			
			if ($status == 1)
			{
				$auditText = "Leave application approved by hr";
				// #Query the  leave_config days for value
				if (!empty($credit))
				{
					$credit->leave_balance = $newBalance;
					$credit->update();
				}
				
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
			}
			else
			{
				// get leave type value
				$leaveTypes = LeaveType::where(
					[
						'id' => $leaveId->leave_type_id,
						'status' => 1
					])->first();
				// send email to manager
				if (!empty($detailsArray['email']))
					Mail::to(
						$detailsArray['email'])->send(
						new leave_applications(
							$detailsArray['first_name'],
							$leaveTypes->name,
							$detailsArray['email'],
							$hrDetails->first_name." ".$hrDetails->surname)
					);
			}

        }
		elseif ($leaveId->status == 5)  // payroll officer approving check if other approval steps are available
		{
            $status = 1;
			$auditText = "Leave application approved by payroll officer";
			// #Query the  leave_config days for value
			$credit = leave_credit::getLeaveCredit($leaveId->hr_id, $leaveId->leave_type_id);
			if (!empty( $credit))
			{
				$credit->leave_balance = $newBalance;
				$credit->update();
			}
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
        }
		$leaveId->status = $status;
		$leaveId->update(); 
		// update leave history
		LeaveHistoryAuditController::store(
			$auditText ,
			'',
			$leaveBalance,
			$daysApplied,
			$newBalance,
			$leaveId->leave_type_id,
			$leaveId->hr_id
		);
        AuditReportsController::store(
            'Leave Management',
            'leave_approval Informations accepted',
            "Edited by User: $leaveId->hr_id",
            0
        );

        return back()->with('success_application', "leave application was successful.");
    }
	
	//// optimized approval code
	public function approveLeave($leaveId)
	{
		$hrDetails = HRPerson::where('id', $leaveId->hr_id)->where('status', 1)->first();
		$daysApplied = $leaveId->leave_taken;
		$config = $this->getApprovalConfig();

		$credit = leave_credit::getLeaveCredit($leaveId->hr_id, $leaveId->leave_type_id);
		$leaveBalance = !empty($credit) ? $credit->leave_balance : 0;
		$newBalance = !empty($credit) ? $leaveBalance - $daysApplied : 0;

		$status = $this->determineNextStatus($leaveId->status, $config);
		$auditText = "";
		$detailsArray = [];

		switch ($status) {
			case 3: // Department Head Approval
				$dept = DivisionLevelFour::where('id', $hrDetails->division_level_4)->first();
				$deptDetails = $dept->load('manager');
				$auditText = "Leave application approved by manager, awaiting department head approval";
				$detailsArray = $this->getManagerDetails($deptDetails->manager);
				break;

			case 4: // HR Approval
				$div = DivisionLevelFive::where('id', $hrDetails->division_level_5)->first();
				$hrDetails = $div->load('hrManager');
				$auditText = "Leave application approved by department head, awaiting HR approval";
				$detailsArray = $this->getManagerDetails($hrDetails->hrManager);
				break;

			case 5: // Payroll Officer Approval
				$div = DivisionLevelFive::where('id', $hrDetails->division_level_5)->first();
				$payrollDetails = $div->load('payrollOfficer');
				$auditText = "Leave application approved by HR, awaiting payroll officer approval";
				$detailsArray = $this->getManagerDetails($payrollDetails->payrollOfficer);
				break;

			default: // Final Approval
				$auditText = "Leave application approved by final approver";
				$newBalance = $this->updateLeaveBalance($credit, $daysApplied);
				$this->sendLeaveApprovalEmails($hrDetails, $leaveId, $newBalance);
				break;
		}

		if ($status != 1) {
			$this->sendEmailNotification(
				$detailsArray['email'],
				new leave_applications(
					$detailsArray['first_name'],
					$leaveId->leave_type_id,
					$detailsArray['email'],
					$hrDetails->first_name . " " . $hrDetails->surname
				)
			);
		}

		$leaveId->status = $status;
		$leaveId->update();

		LeaveHistoryAuditController::store(
			$auditText,
			'',
			$leaveBalance,
			$daysApplied,
			$newBalance,
			$leaveId->leave_type_id,
			$leaveId->hr_id
		);

		AuditReportsController::store(
			'Leave Management',
			'Leave approval information accepted',
			"Edited by User: $leaveId->hr_id",
			0
		);

		return back()->with('success_application', "Leave application was successful.");
	}

	private function getApprovalConfig()
	{
		$approvals = leave_configuration::first();
		return [
			'requireManagersApproval' => (int)($approvals->require_managers_approval ?? 0),
			'requireDepartmentHeadApproval' => (int)($approvals->require_department_head_approval ?? 0),
			'requireHrApproval' => (int)($approvals->require_hr_approval ?? 0),
			'requirePayrollApproval' => (int)($approvals->require_payroll_approval ?? 0),
		];
	}

	private function determineNextStatus($currentStatus, $config)
	{
		if ($currentStatus == 2 && $config['requireDepartmentHeadApproval']) return 3;
		if ($currentStatus == 3 && $config['requireHrApproval']) return 4;
		if ($currentStatus == 4 && $config['requirePayrollApproval']) return 5;
		return 1; // Final approval
	}

	private function updateLeaveBalance($credit, $daysApplied)
	{
		if (!empty($credit)) {
			$newBalance = $credit->leave_balance - $daysApplied;
			$credit->leave_balance = $newBalance;
			$credit->update();
			return $newBalance;
		}
		return 0;
	}

	private function sendLeaveApprovalEmails($hrDetails, $leaveId, $newBalance)
	{
		$leaveAttachment = $this->viewApplication($leaveId);

		if (!empty($hrDetails->email)) {
			Mail::to($hrDetails->email)->queue(new Accept_application($hrDetails->first_name, $leaveAttachment));
		}

		$leaveNotificationsUsers = LeaveNotifications::getListOfUsers();
		if (!empty($leaveNotificationsUsers)) {
			foreach ($leaveNotificationsUsers as $user) {
				if (!empty($user->hr_id)) {
					$details = HRPerson::where('id', $user->hr_id)->where('status', 1)
						->select('first_name', 'email')->first();
					if (!empty($details->email)) {
						Mail::to($details->email)->queue(new SendLeaveApplicationToHrManager($details->first_name, $leaveAttachment));
					}
				}
			}
		}
	}

	private function sendEmailNotification($email, $mailObject)
	{
		if (!empty($email)) {
			Mail::to($email)->queue($mailObject);
		}
	}

	private function getManagerDetails($manager)
	{
		return [
			'first_name' => $manager->first_name,
			'surname' => $manager->surname,
			'email' => $manager->email,
		];
	}

}