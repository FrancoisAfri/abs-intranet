<?php

namespace App\Http\Controllers;

use App\DivisionLevelTwo;
use App\hr_person;
use App\HRPerson;
use App\Http\Requests;
use APP\leavDetails;
use App\leave_application;
use App\leave_configuration;
use App\leave_credit;
use App\leave_custom;
use App\leave_history;
use App\LeaveType;
use App\Mail\Accept_application;
use App\Mail\leave_applications;
use App\Mail\LeaveRejection;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        $employees = HRPerson::where('status', 1)->get()->load(['leave_types' => function ($query) {
            $query->orderBy('name', 'asc');
        }]);

        $leaveTypes = LeaveType::where('status', 1)->get()->load(['leave_profle' => function ($query) {
            $query->orderBy('name', 'asc');
        }]);

        $data['page_title'] = "leave Types";
        $data['page_description'] = "Leave Management";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Leave Application', 'active' => 1, 'is_module' => 0]
        ];

        #Query to get negative annual leave days for user based on userID and LeaveID
        $negativeannualDays = DB::table('leave_configuration')
            ->select('allow_annual_negative_days')
            ->where('id', 1)
            ->get();
        $negannualDays = $negativeannualDays->first()->allow_annual_negative_days;
        #Query to get negative sick leave days for user based on userID and LeaveID
        $negativesickDays = DB::table('leave_configuration')
            ->select('allow_sick_negative_days')
            ->where('id', 1)
            ->get();
        $negsickDays = $negativesickDays->first()->allow_sick_negative_days;

        $data['negannualDays'] = $negannualDays;
        $data['negsickDays'] = $negsickDays;
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Apply';
        $data['leaveTypes'] = $leaveTypes;
        $data['employees'] = $employees;
        $data['leave_customs'] = $leave_customs;

        AuditReportsController::store('Leave Management', 'Leave Type Page Accessed', "Accessed By User", 0);
        return view('leave.application')->with($data);
    }

    public function show()
    {
		
        $data['page_title'] = "leave Management";
        $data['page_description'] = "Leave Approvals";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => 'leave/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'leave Approval', 'active' => 1, 'is_module' => 0]
        ];

        $people = DB::table('hr_people')->orderBy('id', 'asc')->get();
        $leaveTypes = LeaveType::where('status', 1)->get()->load(['leave_profle' => function ($query) {
            $query->orderBy('name', 'asc');
        }]);

        // left join between leaveApplication & HRPerson & LeaveType
        $loggedInEmplID = Auth::user()->person->id;

        $leaveStatus = array(1 => 'Approved', 2 => 'Require managers approval ', 3 => 'Require department head approval', 4 => 'Require hr approval', 5 => 'Require payroll approval', 6 => 'rejected', 7 => 'rejectd_by_department_head', 8 => 'rejectd_by_hr', 9 => 'rejectd_by_payroll', 10 => 'Cancelled');

        $leaveApplication = DB::table('leave_application')
            ->select('leave_application.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname', 'leave_types.name as leavetype', 'hr_people.manager_id as manager', 'leave_credit.leave_balance as leave_Days')
            ->leftJoin('hr_people', 'leave_application.hr_id', '=', 'hr_people.id')
            ->leftJoin('leave_types', 'leave_application.leave_type_id', '=', 'leave_types.id')
            ->leftJoin('leave_credit', 'leave_application.hr_id', '=', 'leave_credit.hr_id')
            ->where('hr_people.manager_id', $loggedInEmplID)
            ->whereNotIn('leave_application.status', [1, 6, 7, 8, 9, 10])
            ->orderBy('leave_application.hr_id')
            ->get();

        $data['leaveStatus'] = $leaveStatus;
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Approval';
        $data['leaveTypes'] = $leaveTypes;
        //$data['employees'] = $employees;
        $data['leaveApplication'] = $leaveApplication;

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
		//UserAccess
		$userAccess = DB::table('security_modules_access')
                ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
                ->where('code_name', 'leave')
                ->where('user_id', Auth::user()->person->user_id)
                ->first();
				
        // query the leave congif table and bring back the values
        $approvals = DB::table('leave_configuration')
            ->select('require_managers_approval', 'require_department_head_approval', 'require_hr_approval', 'require_payroll_approval')
            ->first();
        // query the hrperon  model and bring back the values of the managerg
        $hrDetails = HRPerson::where('id', $hrID)->where('status', 1)->first();
		
		//return $hrDetails;

        if ($approvals->require_managers_approval == 1) {
            # code...
            // query the hrperon  model and bring back the values of the manager
            $managerDetails = HRPerson::where('id', $hrDetails->manager_id)->where('status', 1)
                ->select('first_name', 'surname', 'email')
                ->first();
            if ($managerDetails == null) {
                $details = array('status' => 1, 'first_name' => $hrDetails->first_name, 'surname' => $hrDetails->surname, 'email' => $hrDetails->email);
                return $details;
            } else {
                // array to store manager details
                $details = array('status' => 2, 'first_name' => $managerDetails->firstname, 'surname' => $managerDetails->surname, 'email' => $managerDetails->email);
                return $details;
            }
        } elseif ($approvals->require_department_head_approval == 1) {
            # code...  division_level_twos
            // query the hrperon  model and bring back the values of the manager
            $Dept = DivisionLevelTwo::where('id', $hrDetails->division_level_2)->get()->first();
            $msamgerDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
                ->select('first_name', 'surname', 'email')
                ->first();

            if ($msamgerDetails == null) {
                $details = array('status' => 1, 'first_name' => $hrDetails->first_name, 'surname' => $hrDetails->surname, 'email' => $hrDetails->email);
                return $details;
            } else {
                // array to store manager details
                $details = array('status' => 3, 'first_name' => $msamgerDetails->firstname, 'surname' => $msamgerDetails->surname, 'email' => $msamgerDetails->email);
                return $details;
            }
        } #code here .. Require Hr
        elseif($userAccess->access_level == 5 && $userAccess->access_level == 4) {
            $details = array('status' => 1, 'first_name' => $hrDetails->first_name, 'surname' => $hrDetails->surname, 'email' => $hrDetails->email);
            return $details;
        }else{
				$managerDetails = HRPerson::where('id', $hrDetails->manager_id)->where('status', 1)
                ->select('first_name', 'surname', 'email')
                ->first();
            if ($managerDetails == null) {
                $details = array('status' => 2, 'first_name' => $hrDetails->first_name, 'surname' => $hrDetails->surname, 'email' => $hrDetails->email);
                return $details;
            } else {
                // array to store manager details
                 $details = array('status' => 2, 'first_name' => $managerDetails->firstname, 'surname' => $managerDetails->surname, 'email' => $managerDetails->email);
                return $details;
			}
		
		}
    }

    #function to get available days for user based on userID and LeaveID

    public function availableDays($hrID, $typID)
    {

        $balance = DB::table('leave_credit')
            ->select('leave_balance')
            ->where('hr_id', $hrID)
            ->where('leave_type_id', $typID)
            ->get();

        return !empty($balance->first()->leave_balance) ? $balance->first()->leave_balance / 8 : '';
    }

    public function day(Request $request, leave_application $levApp)
    {
        $this->validate($request, [
            'hr_person_id' => 'bail|required',
            'leave_type' => 'bail|required',
            'application_type' => 'bail|required',
            //'day' => 'bail|required',
            'description' => 'bail|required',
        ]);

        $leaveApp = $request->all();
        unset($leaveApp['_token']);

        $negDays = leave_configuration::where('id', 1)->first();
        $study = $negDays->document_compulsory_on_Study_leave_application;
        //return $study;
        $sickdays = $negDays->document_compulsory_when_two_sick_leave_8_weeks;

        $anualdays = $negDays->allow_annual_negative_days;
        if ($anualdays = null) {
            $anualdays = 0;
        } else
            $anualdays = $negDays->allow_annual_negative_days * 8;

        $sickdays = $negDays->allow_sick_negative_days;
        if ($sickdays = null) {
            $sickdays = 0;
        } else
            $sickdays = $negDays->allow_sick_negative_days * 8;

        $ApplicationDetails = array();
        $status = array();
        $hrID = $leaveApp['hr_person_id'];
        $typID = $leaveApp['leave_type'];

        $managerDetails = HRPerson::where('id', $hrID)
            ->select('manager_id')->first();
        $managerID = $managerDetails['manager_id'];

        $Details = leave_credit::where('hr_id', $hrID)
            ->where('leave_type_id', $typID)
            ->first();
        $leave_balance = $Details['leave_balance'];

        $levApp->leave_type_id = $request->input('leave_type');
        // Get leavetype Id from dropbbox
        $tyop = $leaveApp['leave_type'];

        //query the hr table based on employeeId
        $HRpeople = HRPerson::find($hrID);
        $USername = $HRpeople->first_name;

        // separate day range
        $day = $leaveApp['day'];
        $dates = explode(' - ', $day);
        $start_date = str_replace('/', '-', $dates[0]);
        $start_date = strtotime($start_date);
        $end_date = str_replace('/', '-', $dates[1]);
        $end_date = strtotime($end_date);
        //return $start_date;
        //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
        //We add one to inlude both dates in the interval.
        $diffrenceDays = ($end_date - $start_date) / 86400 + 1;

        // save dates
        // calculate public holidays and weekends
        $iNonweek = 0;
        $aPublicHolidays = array();
        $aPublicHolidays = DB::table('public_holidays')->pluck('day');

        # Add Easter Weekend to list of public holidays

        $iEasterSunday = easter_date(date("Y", strtotime($end_date)));
        $aPublicHolidays[] = $iEasterSunday - (2 * 3600 * 24);
        $aPublicHolidays[] = $iEasterSunday + (3600 * 24);

        //return $aPublicHolidays;

        for ($i = $start_date; $i <= $end_date; $i = $i + 86400) {
            $aPublic = array();

            foreach ($aPublicHolidays as $iKey => $sValue) {
                $sDay = date("Y", $i) . "-" . date("m", $sValue) . "-" . date("d", $sValue);

                $iDay = strtotime($sDay);
                $aPublic[$iDay] = 0;
            }
            if (((date("w", $i) == 6) || (date("w", $i) == 0)))
                $iNonweek++;
            if (array_key_exists($i, $aPublic) && ((date("w", $i) != 6) && (date("w", $i) != 0)))
                $iNonweek++;

            //
            if (array_key_exists($i - 86400, $aPublic) && (date("w", $i) == 1))
                if (array_key_exists($i, $aPublic)) {

                } else
                    $iNonweek++;
        }
        //$iDiff = strtotime($start_date) - strtotime();
        $iDiff = $end_date - $start_date;

        $diffDays = ($iDiff / 86400) - $iNonweek + 1; // use this for days diffrence
        $iDays = $diffDays * 8;
        $current_balance = $leave_balance - $diffDays;

        // call the function
        $ApplicationDetails = LeaveApplicationController::ApplicationDetails(0, $hrID);
        //return $ApplicationDetails;

        $statusnames = LeaveApplicationController::status();

        $applicatiionStaus = $ApplicationDetails['status'];
        // return $applicatiionStaus;
        $levtype = $request->input('leave_type');

        $levApp->start_date = $start_date;
        $levApp->end_date = $end_date;
        $levApp->leave_days = $iDays;
        $levApp->leave_type_id = $request->input('leave_type');
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
                $request->file('supporting_docs')->storeAs('levApp', $fileName);
                //Update file name in hr table
                $levApp->supporting_docs = $fileName;
                $levApp->update();
            }
        }


        // send email to manager
        Mail::to($ApplicationDetails['email'])->send(new leave_applications($ApplicationDetails['first_name'], $ApplicationDetails['surname'], $ApplicationDetails['email']));

        #$action='',$descriptionAction ='',$previousBalance='',$transcation='' ,$current_balance ='',$leave_type ='')
        AuditReportsController::store('Leave Management', 'Leave day application ', "Accessed By User", 0);
        #leave history audit
        LeaveHistoryAuditController::store("Day leave application performed by : $USername", '', $leave_balance, $iDays, $current_balance, $levtype, $hrID);
        return back()->with('success_application', "leave application was successful.");
        //return view('leave.application')->with($levTypVar);
    }

    public function hours(Request $request, leave_application $levApp)
    {
        $this->validate($request, [
        ]);
        $leaveApp = $request->all();
        $hrID = $leaveApp['hr_person_id'];
        $time_from = $leaveApp['time_from'];
        $time_to = $leaveApp['time_to'];
        $approveDetails = array();
        unset($leaveApp['_token']);

        $ApplicationDetails = array();
        $status = array();

        $date = $leaveApp['date'];
        $start_time = strtotime($date . ' ' . $time_from);
        $end_time = strtotime($date . ' ' . $time_to);
        $date = strtotime($date);
        //return $date . ' ' . $start_time .  ' ' . $end_time;

        //Query the Holiday table and return the days
        $public_holiday = DB::table('public_holidays')->pluck('day');

        $diffrencetime = ($end_time - $start_time) / 3600;
        // $diffrenceTime = strtotime($diffrencetime);
        //#calculate
        // #save the start and end date
        //Upload supporting Documents
        if ($request->hasFile('supporting_docs')) {
            $fileExt = $request->file('supporting_docs')->extension();
            if (in_array($fileExt, ['doc', 'docx', 'pdf']) && $request->file('supporting_docs')->isValid()) {
                $fileName = time() . "_supporting_docs." . $fileExt;
                $request->file('supporting_docs')->storeAs('levApp', $fileName);
                //Update file name in hr table
                $levApp->supporting_docs = $fileName;
                $levApp->update();
            }
        }
        $hrID = $request->input('hr_person_id');
        $managerDetails = HRPerson::where('id', $hrID)
            ->select('manager_id')
            ->get()->first();

        $managerID = $managerDetails['manager_id'];

        // $ApplicationDetails =  LeaveApplicationController::ApplicationDetails(0, $request->input('hr_person_id'));
        $ApplicationDetails = LeaveApplicationController::ApplicationDetails(0, $hrID);
        // return $ApplicationDetails;
        $statusnames = LeaveApplicationController::status();
        $applicatiionStaus = $ApplicationDetails['status'];


        // $status = $statusnames[$applicatiionStaus];
        $employees = $request->input('hr_person_id');
        $typID = $request->input('leave_type');
        $HRpeople = HRPerson::find($employees);
        $USername = $HRpeople->first_name;
        #
        $Details = leave_credit::where('hr_id', $employees)
            ->where('leave_type_id', $typID)
            ->first();
        $leave_balance = $Details['leave_balance'];
        #
        $levApp->leave_type_id = $typID;
        $levApp->hr_id = $employees;
        $levApp->notes = $request->input('description');
        $levApp->status = $applicatiionStaus;
        $levApp->start_date = $date;
        $levApp->end_date = $date;
        $levApp->start_time = $start_time;
        $levApp->end_time = $end_time;
        $levApp->leave_hours = $diffrencetime;
        $levApp->manager_id = $managerID;
        $levApp->save();

        #mail
        Mail::to($ApplicationDetails['email'])->send(new leave_applications($ApplicationDetails['first_name'], $ApplicationDetails['surname'], $ApplicationDetails['email']));

        #$action='',$descriptionAction ='',$previousBalance='',$transcation='' ,$current_balance ='',$leave_type ='')
        AuditReportsController::store('Leave Management', 'Leave hours application ', "Accessed By User", 0);
        LeaveHistoryAuditController::store("Hours leave application performed by : $USername", 0, $leave_balance, 0, $leave_balance, $typID, $employees);

        return back()->with('success_application', "leave application was successful.");
    }

    //Function to accept leave applications
    public function AcceptLeave(Request $request, leave_application $id, leave_history $levHist, leave_credit $credit, leave_configuration $leave_conf)
    {
		$userAccess = DB::table('security_modules_access')
                ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
                ->where('code_name', 'leave')
                ->where('user_id', Auth::user()->person->user_id)
                ->first();
		//return 	$userAccess;	
				
        $loggedInEmplID = Auth::user()->person->id;
        $status = $id->status;
        $iD = $id->id;

        $LevTid = $id->leave_type_id;
        $hriD = $id->hr_id;
        #query the hr person table
        $usedetails = HRPerson::where('id', $hriD)
            ->select('first_name', 'surname', 'email')
            ->first();

        $firstname = $usedetails['first_name'];
        $surname = $usedetails['surname'];
        $email = $usedetails['email'];
        $levTyp = $id->leave_type_id;
        $leave_appDetails = leave_application::where('id', $iD)->first();
        // #Query the the leave_config days for value
        $negDays = leave_configuration::where('id', 1)->first();
        $hrID = $id['hr_id'];
        $typID = $id['leave_type_id'];
        $Details = leave_credit::where('hr_id', $hrID)
            ->where('leave_type_id', $typID)
            ->first();
        $leave_balance = $Details['leave_balance'];
        # check whose in the list of approving an application before writing into the db

        $managerApproval = $negDays['require_managers_approval'];
        $managerApproval = $negDays['require_department_head_approval'];
        $managerApproval = $negDays['require_managers_approval'];

        if ($levTyp == 1) {
            $anualdays = $negDays->allow_annual_negative_days * 8;
            $daysApplied = $id['leave_days'];
            $bal = $daysApplied + $anualdays;
            $nwBal = $leave_balance - $bal;

            DB::table('leave_credit')
                ->where('hr_id', $hrID)
                ->where('leave_type_id', $typID)
                ->update(['leave_balance' => $nwBal]);
        } elseif ($levTyp == 5) {
            $sickdays = $negDays->allow_sick_negative_days * 8;
            $daysApplied = $id['leave_days'];
            $bal = $daysApplied + $sickdays;
            $nwBal = $leave_balance - $bal;
            DB::table('leave_credit')
                ->where('hr_id', $hrID)
                ->where('leave_type_id', $typID)
                ->update(['leave_balance' => $nwBal]);
        } else
            #Get the user leave balance
            $daysApplied = $id['leave_days'];
        #calculations
        #subract current balance from the one applied for
        $newBalance = $leave_balance - $daysApplied;
        #save new leave balance
        DB::table('leave_credit')
            ->where('hr_id', $hrID)
            ->where('leave_type_id', $typID)
            ->update(['leave_balance' => $newBalance]);
        $levHist->description_action = $leave_appDetails;
        $levHist->previous_balance = $leave_balance;
        $levHist->save();
        $approvals = leave_configuration::where('id', 1)
            ->select('require_managers_approval', 'require_department_head_approval')
            ->get()
			->first();

        $ManHed = $approvals->first()->require_managers_approval;
        $DepHead = $approvals->first()->require_department_head_approval;
        if ($status == 1 && $ManHed) {
            DB::table('leave_application')
                ->where('id', $iD)
                ->update(['status' => 2]);
        } elseif ($status == 2 && $DepHead == 1) {
            DB::table('leave_application')
                ->where('id', $iD)
                ->update(['status' => 3]);
        } else {
			
            DB::table('leave_application')
                ->where('id', $iD)
                ->update(['status' => 1]);
        }
        #send email to the user informing that the leave has been accepted
        Mail::to($email)->send(new Accept_application($firstname, $surname, $email));

        LeaveHistoryAuditController::store("leave application Approved", 0, $leave_balance, $daysApplied, $newBalance, $LevTid, $hrID);
        AuditReportsController::store('Leave Management', 'leave_approval Informations accepted', "Edited by User: $levHist->hr_id", 0);

        return back()->with('success_application', "leave application was successful.");
    }

    public function reject(Request $request, leave_application $levReject)
    {
        $this->validate($request, [
            // 'description' => 'numeric',
        ]);
        $leaveData = $request->all();
        unset($leaveData['_token']);

        $usedetails = HRPerson::where('id', $levReject->hr_id)
            ->select('first_name', 'surname', 'email')
            ->first();
        $firstname = $usedetails['first_name'];
        $surname = $usedetails['surname'];
        $email = $usedetails['email'];

        $levReject->reject_reason = $request->input('description');
        $levReject->status = 6;
        $levReject->update();
        #send rejection email
        Mail::to($email)->send(new LeaveRejection($firstname, $surname, $email));
        AuditReportsController::store('Leave Management: ', 'leave rejected', "By User", 0);
        //LeaveHistoryAuditController::store("leave application Rejected", 0, 0, 0, 0, $levReject->leave_type_id, $levReject->hr_id);
        return response()->json();
    }
}