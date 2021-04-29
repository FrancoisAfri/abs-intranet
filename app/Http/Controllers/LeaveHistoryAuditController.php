<?php

namespace App\Http\Controllers;

use App\activity;
use App\CompanyIdentity;
use App\contacts_company;
use App\HRPerson;
use App\programme;
use App\projects;
use App\User;
use App\DivisionLevel;
use App\LeaveType;
use App\leave_custom;
use App\leave_configuration;
use App\leave_application;
use App\hr_person;
use App\AuditTrail;
use App\leave_history;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class LeaveHistoryAuditController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function show() {
        $data['page_title'] = "Leave Audit Report";
        $data['page_description'] = "Leave History Audit";
        $data['breadcrumb'] = [
                ['title' => 'Leave Management', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Leave History Audit', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Leave History Audit';

        $users = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
        $employees = HRPerson::where('status', 1)->get()->load(['leave_types' => function($query) {
                $query->orderBy('name', 'asc');
            }]);

        $leaveTypes = LeaveType::where('status', 1)->get()->load(['leave_profle' => function($query) {
                $query->orderBy('name', 'asc');
            }]);

        $data['leaveTypes'] = $leaveTypes;
        $data['employees'] = $employees;
        $data['users'] = $users;
        AuditReportsController::store('Leave Management', 'Reports page accessed', "Accessed by User", 0);
        return view('leave.leave_search')->with($data);
    }

    public static function store($action = '', $descriptionAction = '', $previousBalance = 0, $transcation = 0, $current_balance = 0, $leave_type = 0, $hrID = 0) {
        $user = Auth::user()->load('person');
		if (!empty($user))
		{
			$userID = $user->person->id;
			$userName = $user->person->first_name." ".$user->person->surname;
		}
		else 
		{
			$userID = 0;
			$userName = '';
		}
        $leave_history = new leave_history();
        //$leave_history
        $leave_history->hr_id = $hrID;
        $leave_history->added_by = $userID;
        $leave_history->added_by_name = $userName;
        $leave_history->action = $action;
        $leave_history->description_action = $descriptionAction;
        $leave_history->previous_balance = $previousBalance;
        $leave_history->transcation = $transcation;
        $leave_history->current_balance = $current_balance;
        $leave_history->leave_type_id = $leave_type;
        $leave_history->action_date = time();
        #save Audit
        $leave_history->save();
    }

    #draw history report according to search critea
    #
	public function reports() {
        
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
        $leaveTypes = LeaveType::where('status', 1)->orderBy('name', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();

        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Reports';
        $data['leaveTypes'] = $leaveTypes;
        $data['division_levels'] = $divisionLevels;
        $data['employees'] = $employees;
		$data['page_title'] = "Report";
        $data['page_description'] = "Leave Reports";
        $data['breadcrumb'] = [
                ['title' => 'Leave Management', 'path' => '/leave/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], ['title' => 'Leave Reports', 'active' => 1, 'is_module' => 0]
        ];
        AuditReportsController::store('Leave History Audit', 'Reports page accessed', "Accessed by User", 0);
        return view('leave.reports.leave_report_index')->with($data);
    }
    #draw history report according to search critea

    public function getlevhistoryReport(Request $request) {
        $this->validate($request, [
        ]);
        $request = $request->all();
        unset($request['_token']);

        $actionFrom = $actionTo = 0;
        $hr_person_id = $request['hr_person_id'];
        $LevTypID = !empty($request['leave_types_id']) ? $request['leave_types_id'] : 0;
        $actionDate = $request['action_date'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $historyAudit = DB::table('leave_history')
			->select('leave_history.*', 'hr_people.employee_number as employee_number '
			, 'hr_people.first_name as firstname'
			, 'hr_people.surname as surname', 'leave_types.name as leave_type')
			->leftJoin('hr_people', 'leave_history.hr_id', '=', 'hr_people.id')
			->leftJoin('leave_types', 'leave_history.leave_type_id', '=', 'leave_types.id')
			->where(function ($query) use ($actionFrom, $actionTo) {
				if ($actionFrom > 0 && $actionTo > 0) {
					$query->whereBetween('leave_history.action_date', [$actionFrom, $actionTo]);
				}
			})
			->where(function ($query) use ($hr_person_id) {
				if (!empty($hr_person_id)) {
					$query->where('leave_history.hr_id', $hr_person_id);
				}
			})
			->where(function ($query) use ($LevTypID) {
				if (!empty($LevTypID)) {
					$query->where('leave_history.leave_type_id', $LevTypID);
				}
			})
			->orderBy('hr_people.first_name')
			->orderBy('hr_people.surname')
			->orderBy('leave_types.name')
			->get();

        $data['actionFrom'] = $actionFrom;
        $data['hr_person_id'] = $hr_person_id;
        $data['actionDate'] = $actionDate;
        $data['leave_types_id'] = $LevTypID;
        $data['historyAudit'] = $historyAudit;
        $data['page_title'] = "Leave history Audit Report";
        $data['page_description'] = "Leave history Audit Report";
        $data['breadcrumb'] = [
                ['title' => 'Leave Management', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Leave History Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Reports';
        AuditReportsController::store('Leave Management', 'Viewed Leave History report Results', "view Audit Results", 0);
        return view('leave.reports.leave_history_report')->with($data);
    }
	#
    public function printlevhistoReport(Request $request) {

        $actionFrom = $actionTo = 0;
        $hr_person_id = $request['hr_person_id'];
        $LevTypID = !empty($request['leave_types_id']) ? $request['leave_types_id'] : 0;
        $actionDate = $request['actionDate'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $historyAudit = DB::table('leave_history')
			->select('leave_history.*', 'hr_people.employee_number as employee_number '
			, 'hr_people.first_name as firstname'
			, 'hr_people.surname as surname', 'leave_types.name as leave_type')
			->leftJoin('hr_people', 'leave_history.hr_id', '=', 'hr_people.id')
			->leftJoin('leave_types', 'leave_history.leave_type_id', '=', 'leave_types.id')
			->where(function ($query) use ($actionFrom, $actionTo) {
				if ($actionFrom > 0 && $actionTo > 0) {
					$query->whereBetween('leave_history.action_date', [$actionFrom, $actionTo]);
				}
			})
			->where(function ($query) use ($hr_person_id) {
				if (!empty($hr_person_id)) {
					$query->where('leave_history.hr_id', $hr_person_id);
				}
			})
			->where(function ($query) use ($LevTypID) {
				if (!empty($LevTypID)) {
					$query->where('leave_history.leave_type_id', $LevTypID);
				}
			})
			->orderBy('hr_people.first_name')
                ->orderBy('hr_people.surname')
                ->orderBy('leave_types.name')
			->get();

        $data['historyAudit'] = $historyAudit;
        $data['page_title'] = "Leave history Audit Report";
        $data['page_description'] = "Leave history Audit Report";
        $data['breadcrumb'] = [
                ['title' => 'Leave Management', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Leave History Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Reports';
        $user = Auth::user()->load('person');
		$companyDetails = CompanyIdentity::systemSettings();
        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyDetails['company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
		
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['user'] = $user;

        AuditReportsController::store('Leave Management', 'Printed Leave History Report Results', "view Audit Results", 0);
        return view('leave.reports.leave_history_print')->with($data);
    }
    public function cancelledLeaves(Request $request) {

        $reportData = $request->all();
        return $this->getCancelledLeavesReport($reportData['hr_person_id'], $reportData['leave_types_id'], $reportData['action_date'], false);
    }

    public function cancelledLeavesPrint(Request $request) {
        
        $reportData = $request->all();
        return $this->getCancelledLeavesReport($reportData['hr_person_id'], $reportData['leave_types_id'], $reportData['action_date'], true);
    }

    private function getCancelledLeavesReport($employeeID, $leaveTypeID, $action_date, $print = false) {
        $data['employeeID'] = $employeeID;
        $data['leaveTypeID'] = $leaveTypeID;
        $data['action_date'] = $action_date;
		$actionFrom = $actionTo = 0;
        $employeeID = !empty($employeeID) ? (int) $employeeID : 0;
        $leaveTypeID = !empty($leaveTypeID) ? (int) $leaveTypeID : 0;
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $leaveApplications = leave_application::where('status', 10)
            ->where(function ($query) use($employeeID) {
                if ($employeeID > 0) {
                    $query->where('hr_id', $employeeID);
                }
            })
            ->where(function ($query) use($leaveTypeID) {
                if ($leaveTypeID > 0) {
                    $query->where('leave_type_id', $leaveTypeID);
                }
            })
			->where(function ($query) use ($actionFrom, $actionTo) {
				if ($actionFrom > 0 && $actionTo > 0) {
					$query->whereBetween('start_date', [$actionFrom, $actionTo]);
				}
			})
            ->limit(100)
            ->with('person', 'leavetpe', 'canceller')
            ->get();
        $data['page_title'] = "Leave Report";
        $data['page_description'] = "Cancelled Leaves Report";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Leave Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Reports';
        $data['leaveApplications'] = $leaveApplications;

        if ($print) {
            AuditReportsController::store('Audit', 'Generate Cancelled Leaves Report', "Generated by user", 0);
            $companyDetails = CompanyIdentity::systemSettings();
            $data['printing_person'] = Auth::user()->person->full_name;
            $data['company_logo'] = $companyDetails['company_logo_url'];
            $data['date'] = date("d-m-Y");
            AuditReportsController::store('Leave Management', 'Printed Leaves Cancellation Report', "Generated by user", 0);
            return view('leave.reports.concelled_leaves_report_print')->with($data);
        }
        else {
            AuditReportsController::store('Leave Management', 'Generate Cancelled Leaves Report', "Generated by user", 0);
            return view('leave.reports.cancelled_leaves_report')->with($data);
        }
    }

    public function leavebalance(Request $request) {
        $this->validate($request, [
                #code here ....
        ]);
        $request = $request->all();
        unset($request['_token']);

        $userID = !empty($request['hr_person_id']) ? $request['hr_person_id'] : 0;
        $LevTypID = !empty($request['leave_types_id']) ? $request['leave_types_id'] : 0;
		
        #Query the leave credit
        $credit = DB::table('hr_people')
                ->select('hr_people.*', 'leave_credit.hr_id as userID', 'leave_credit.leave_balance as Balance', 'leave_credit.leave_type_id as LeaveID', 'leave_types.name as leaveType')
                ->leftJoin('leave_credit', 'leave_credit.hr_id', '=', 'hr_people.id')
                ->leftJoin('leave_types', 'leave_credit.leave_type_id', '=', 'leave_types.id')
                ->where('hr_people.status', 1)
                ->where(function ($query) use ($userID) {
                    if (!empty($userID)) {
                        $query->where('hr_people.id', $userID);
                    }
                })
                ->where(function ($query) use ($LevTypID) {
                    if (!empty($LevTypID)) {
                        $query->where('leave_credit.leave_type_id', $LevTypID);
                    }
                })
                ->orderBy('hr_people.first_name')
                ->orderBy('hr_people.surname')
                ->orderBy('leave_types.name')
                ->get();
        $data['userID'] = $userID;
        $data['LevTypID'] = $LevTypID;
        $data['credit'] = $credit;
        $data['page_title'] = "Leave Reports";
        $data['page_description'] = "Leave Report";
        $data['breadcrumb'] = [
                ['title' => 'Leave Management', 'path' => '/leave/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Leave Balance Audit', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Reports';
        AuditReportsController::store('Leave Management', 'Viewed Leave Balance Report Results', "view Reports Results", 0);
        return view('leave.reports.leave_report_balance')->with($data);
    }

    //
    public function printlevbalReport(Request $request) {

        $userID = $request['userID'];
        $LevTypID = $request['LevTypID'];

        $credit = DB::table('hr_people')
                ->select('hr_people.*', 'leave_credit.hr_id as userID', 'leave_credit.leave_balance as Balance', 'leave_credit.leave_type_id as LeaveID', 'leave_types.name as leaveType')
                ->leftJoin('leave_credit', 'leave_credit.hr_id', '=', 'hr_people.id')
                ->leftJoin('leave_types', 'leave_credit.leave_type_id', '=', 'leave_types.id')
                ->where('hr_people.status', 1)
                ->where(function ($query) use ($userID) {
                    if (!empty($userID)) {
                        $query->where('hr_people.id', $userID);
                    }
                })
                ->where(function ($query) use ($LevTypID) {
                    if (!empty($LevTypID)) {
                        $query->where('leave_credit.leave_types_id', $LevTypID);
                    }
                })
                ->orderBy('hr_people.first_name')
                ->orderBy('hr_people.surname')
                ->orderBy('leave_types.name')
                ->get();

        $data['user_id'] = $userID;
        $data['LevTypID'] = $LevTypID;
        $data['credit'] = $credit;
        $data['page_title'] = "Leave history Audit Report";
        $data['page_description'] = "Leave history Audit Report";
        $data['breadcrumb'] = [
                ['title' => 'Leave Management', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Leave History Audit', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Reports';
        $user = Auth::user()->load('person');
		$companyDetails = CompanyIdentity::systemSettings();
        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyDetails['company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
		
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['user'] = $user;
        AuditReportsController::store('Leave Management', 'Printed Leave Balance Report Results', "view Audit Results", 0);
        return view('leave.reports.leave_balance_print')->with($data);
    }

    public function leaveAllowance(Request $request) {
        $this->validate($request, [
                #validation code here ....
        ]);
        $request = $request->all();
        unset($request['_token']);
 
        $userID = $request['hr_person_id'];
        $LevTypID = $request['leave_types_id'];
		
        $allowances = DB::table('hr_people')
                ->select('hr_people.*', 'type_profile.max as max'
				, 'type_profile.min as min'
				, 'leave_types.name as leave_type_name')
                ->leftJoin('type_profile', 'type_profile.leave_profile_id', '=', 'type_profile.leave_profile_id')
                ->leftJoin('leave_types', 'type_profile.leave_type_id', '=', 'leave_types.id')
                ->where('hr_people.status', 1)
                ->where(function ($query) use ($userID) {
                    if (!empty($userID)) {
                        $query->where('hr_people.id', $userID);
                    }
                })
                ->where(function ($query) use ($LevTypID) {
                    if (!empty($LevTypID)) {
                        $query->where('type_profile.leave_type_id', $LevTypID);
                    }
                })
                ->orderBy('type_profile.leave_type_id')
                ->get();
				
        $data['userID'] = $userID;
        $data['LevTypID'] = $LevTypID;
        $data['allowances'] = $allowances;
        $data['page_title'] = "Leave Reports";
        $data['page_description'] = "Leave Report";
        $data['breadcrumb'] = [
                ['title' => 'Leave Management', 'path' => '/leave/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],
                ['title' => 'Leave Taken Audit', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Reports';
        AuditReportsController::store('Leave Management', 'Viewed Leave Allocation Report Results', "view Reports Results", 0);
        return view('leave.reports.leave_allowance report')->with($data);
    }
	//// allowance Printed
	public function leaveAllowancePrint(Request $request) {
        $this->validate($request, [
                #validation code here ....
        ]);
        $request = $request->all();
        unset($request['_token']);

		$userID = $request['hr_person_id'];
        $LevTypID = $request['leave_types_id'];
		
        $allowances = DB::table('hr_people')
			->select('hr_people.*', 'type_profile.max as max'
			, 'type_profile.min as min'
			, 'leave_types.name as leave_type_name')
			->leftJoin('type_profile', 'type_profile.leave_profile_id', '=', 'type_profile.leave_profile_id')
			->leftJoin('leave_types', 'type_profile.leave_type_id', '=', 'leave_types.id')
			->where('hr_people.status', 1)
			->where(function ($query) use ($userID) {
				if (!empty($userID)) {
					$query->where('hr_people.id', $userID);
				}
			})
			->where(function ($query) use ($LevTypID) {
				if (!empty($LevTypID)) {
					$query->where('type_profile.leave_type_id', $LevTypID);
				}
			})
			->orderBy('type_profile.leave_type_id')
			->get();
			
        $data['allowances'] = $allowances;
        $data['page_title'] = "Leave Allowance";
        $data['page_description'] = "Report";
        $data['breadcrumb'] = [
                ['title' => 'Leave Management', 'path' => '/leave/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Reports';
		$user = Auth::user()->load('person');
		$companyDetails = CompanyIdentity::systemSettings();
        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyDetails['company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
		
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['user'] = $user;
        AuditReportsController::store('Leave Management', 'Printed Leave Allowance Report Results', "view Reports Results", 0);
        return view('leave.reports.leave_allowance_print_report')->with($data);
    }

    public function taken(Request $request) {
        $this->validate($request, [
                #validation code here ....
        ]);
        $request = $request->all();
        unset($request['_token']);
		$actionFrom = $actionTo = 0;
        $userID = !empty($request['hr_person_id']) ? $request['hr_person_id'] : 0;
        $LevTypID = !empty($request['leave_types_id']) ? $request['leave_types_id'] : 0;
		$actionDate = $request['action_date'];
		if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $leaveTakens = DB::table('leave_application')
                ->select('leave_application.*', 'hr_people.employee_number as employee_number'
				, 'hr_people.first_name'
				, 'hr_people.surname'
				, 'leave_types.name as leave_type_name')
                ->leftJoin('hr_people', 'leave_application.hr_id', '=', 'hr_people.id')
                ->leftJoin('leave_types', 'leave_application.leave_type_id', '=', 'leave_types.id')
                ->where('hr_people.status', 1)
                ->where(function ($query) use ($userID) {
                    if (!empty($userID)) {
                        $query->where('hr_people.id', $userID);
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

        $data['userID'] = $userID;
        $data['LevTypID'] = $LevTypID;
        $data['actionDate'] = $actionDate;
        $data['leaveTakens'] = $leaveTakens;
        $data['page_title'] = "Leave Reports";
        $data['page_description'] = "Leave Report";
        $data['breadcrumb'] = [
                ['title' => 'Leave Management', 'path' => '/leave/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Reports';
		
        AuditReportsController::store('Leave Management', 'Viewed Leave Taken Report Results', "view Reports Results", 0);
        return view('leave.reports.leave_taken report')->with($data);
    }
	// print taken
	public function takenPrint(Request $request) {
        $this->validate($request, [
                #validation code here ....
        ]);
        $request = $request->all();
        unset($request['_token']);

		$actionFrom = $actionTo = 0;
        $userID = !empty($request['hr_person_id']) ? $request['hr_person_id'] : 0;
        $LevTypID = !empty($request['leave_types_id']) ? $request['leave_types_id'] : 0;
		$actionDate = $request['action_date'];
		if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $leaveTakens = DB::table('leave_application')
			->select('leave_application.*', 'hr_people.employee_number as employee_number'
			, 'hr_people.first_name'
			, 'hr_people.surname'
			, 'leave_types.name as leave_type_name')
			->leftJoin('hr_people', 'leave_application.hr_id', '=', 'hr_people.id')
			->leftJoin('leave_types', 'leave_application.leave_type_id', '=', 'leave_types.id')
			->where('hr_people.status', 1)
			->where(function ($query) use ($userID) {
				if (!empty($userID)) {
					$query->where('hr_people.id', $userID);
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

        $data['userID'] = $userID;
        $data['LevTypID'] = $LevTypID;
        $data['leaveTakens'] = $leaveTakens;
        $data['page_title'] = "Leave Reports";
        $data['page_description'] = "Leave Report";
        $data['breadcrumb'] = [
                ['title' => 'Leave Management', 'path' => '/leave/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Reports';
		$user = Auth::user()->load('person');
		$companyDetails = CompanyIdentity::systemSettings();
        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyDetails['company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
		
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['user'] = $user;
        AuditReportsController::store('Leave Management', 'Viewed Leave Taken Report Results', "view Reports Results", 0);
        return view('leave.reports.leave_taken_print_report')->with($data);
    }
    public function leavepaidOut(Request $request) {
        $this->validate($request, [
                #code here ....
        ]);
        $request = $request->all();
        unset($request['_token']);

        $userID = $request['hr_person_id'];
        $LevTypID = $request['leave_types_id'];

        $custom = DB::table('hr_people')
                ->select('hr_people.*', 'type_profile.max as max', 'type_profile.leave_type_id as levID', 'type_profile.leave_profile_id as ProfID', 'leave_customs.hr_id as empID', 'leave_customs.number_of_days as Days', 'leave_types.name as leaveType')
                ->leftJoin('type_profile', 'type_profile.leave_profile_id', '=', 'type_profile.leave_profile_id')
                ->leftJoin('leave_customs', 'hr_people.id', '=', 'leave_customs.hr_id')
                ->leftJoin('leave_types', 'type_profile.leave_type_id', '=', 'leave_types.id')
                ->where('leave_customs.status', 1)
                ->where('hr_people.status', 1)
                ->where(function ($query) use ($userID) {
                    if (!empty($userID)) {
                        $query->where('hr_people.id', $userID);
                    }
                })
                ->where(function ($query) use ($LevTypID) {
                    if (!empty($LevTypID)) {
                        $query->where('type_profile.leave_type_id', $LevTypID);
                    }
                })
                ->orderBy('type_profile.leave_type_id')
                ->get();

        $data['userID'] = $userID;
        $data['LevTypID'] = $LevTypID;
        $data['custom'] = $custom;
        $data['page_title'] = "Leave Reports";
        $data['page_description'] = "Leave Report";
        $data['breadcrumb'] = [
                ['title' => 'Leave Management', 'path' => '/leave/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Leave Taken Audit', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Reports';
        AuditReportsController::store('Leave Management', 'Viewed leave Paid out Report Results', "view Reports Results", 0);
        return view('leave.reports.leave_paid_out report')->with($data);
    }
	
}