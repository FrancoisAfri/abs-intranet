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
use BladeView;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LeaveHistoryAuditController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return BladeView|false|Factory|Application|View
     */
    public function show()
    {
        $data['page_title'] = "Leave Audit Report";
        $data['page_description'] = "Leave History Audit";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Leave History Audit', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Leave History Audit';

        $users = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
        $employees = HRPerson::where('status', 1)->get()->load(['leave_types' => function ($query) {
            $query->orderBy('name', 'asc');
        }]);

        $leaveTypes = LeaveType::where('status', 1)->get()->load(['leave_profle' => function ($query) {
            $query->orderBy('name', 'asc');
        }]);

        $data['leaveTypes'] = $leaveTypes;
        $data['employees'] = $employees;
        $data['users'] = $users;
        AuditReportsController::store('Leave Management', 'Reports page accessed', "Accessed by User", 0);
        return view('leave.leave_search')->with($data);
    }

    /**
     * @param $action
     * @param $descriptionAction
     * @param $previousBalance
     * @param $transcation
     * @param $current_balance
     * @param $leave_type
     * @param $hrID
     * @return void
     */
    public static function store(
        $action = '',
        $descriptionAction = '',
        $previousBalance = 0,
        $transcation = 0,
        $current_balance = 0,
        $leave_type = 0,
        $hrID = 0,
        $isCron = 0,
        $user = 0
    )
    {
        if ($isCron == 0)
            $user = Auth::user()->load('person');


        if (!empty($user)) {

            $userID = $user->person->id;
            $userName = $user->person->first_name . " " . $user->person->surname;

        } else {
            $userID = 0;
            $userName = 'Cron Job';
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

    /**
     * @return BladeView|false|Factory|Application|View
     */
    public function reports()
    {

        $hrID = Auth::user()->id;
        $currentUser = Auth::user()->person->id;
        $userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'leave')->where('security_modules_access.access_level', '>', 3)
            ->where('security_modules_access.user_id', $hrID)->pluck('user_id')->first();
        if (!empty($userAccess))
            $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        else {
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
        AuditReportsController::store('Leave Management', 'Reports page accessed', "Accessed by User", 0);
        return view('leave.reports.leave_report_index')->with($data);
    }
    #draw history report according to search critea

    /**
     * @param Request $request
     * @return BladeView|false|Factory|Application|View
     */
    public function getlevhistoryReport(Request $request)
    {
		$this->validate($request, [
            'division_level_5' => 'required',
        ]);
        $actionFrom = $actionTo = 0;
        $hr_person_id = $request['hr_person_id'];
        $LevTypID = !empty($request['leave_types_id']) ? $request['leave_types_id'] : 0;
        $actionDate = $request['action_date'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->limit(2)->get();
		//
		$div5 = !empty($request['division_level_5']) ? $request['division_level_5'] : 0;
        $div4 = !empty($request['division_level_4']) ? $request['division_level_4'] : 0;
        $div3 = !empty($request['division_level_3']) ? $request['division_level_3'] : 0;
        $div2 = !empty($request['division_level_2']) ? $request['division_level_2'] : 0;
        $div1 = !empty($request['division_level_1']) ? $request['division_level_1'] : 0;
		$userID = !empty($request['hr_person_id']) ? $request['hr_person_id'] : 0;

        if (!empty($userID))
            $employees = $userID;
        elseif (!empty($div1))
            $employees = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('hr_id');
        elseif (!empty($div2))
            $employees = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
        elseif (!empty($div3))
            $employees = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
        elseif (!empty($div4))
            $employees = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
        elseif (!empty($div5))
            $employees = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');
			
        $historyAudit = leave_history::getLeaveHistory($actionFrom, $actionTo, $employees, $LevTypID);
		//return $historyAudit;
		$data['div1'] = $div1;
        $data['div2'] = $div2;
        $data['div3'] = $div3;
        $data['div4'] = $div4;
        $data['div5'] = $div5;
        $data['actionFrom'] = $actionFrom;
        $data['hr_person_id'] = $hr_person_id;
        $data['actionDate'] = $actionDate;
        $data['leave_types_id'] = $LevTypID;
        $data['historyAudit'] = $historyAudit;
		$data['division_levels'] = $divisionLevels;
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

    /**
     * @param Request $request
     * @return BladeView|false|Factory|Application|View
     */
    public function printlevhistoReport(Request $request)
    {
		
		$div5 = !empty($request['div5']) ? $request['div5'] : 0;
        $div4 = !empty($request['div4']) ? $request['div4'] : 0;
        $div3 = !empty($request['div3']) ? $request['div3'] : 0;
        $div2 = !empty($request['div2']) ? $request['div2'] : 0;
        $div1 = !empty($request['div1']) ? $request['div1'] : 0;
        $actionFrom = $actionTo = 0;
        $hr_person_id = $request['hr_person_id'];
        $LevTypID = !empty($request['leave_types_id']) ? $request['leave_types_id'] : 0;
        $actionDate = $request['actionDate'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->limit(2)->get();
		//
		$div5 = !empty($request['div5']) ? $request['div5'] : 0;
        $div4 = !empty($request['div4']) ? $request['div4'] : 0;
        $div3 = !empty($request['div3']) ? $request['div3'] : 0;
        $div2 = !empty($request['div2']) ? $request['div2'] : 0;
        $div1 = !empty($request['div1']) ? $request['div1'] : 0;
		$userID = !empty($request['hr_person_id']) ? $request['hr_person_id'] : 0;

        if (!empty($userID))
            $employees = $userID;
        elseif (!empty($div1))
            $employees = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('hr_id');
        elseif (!empty($div2))
            $employees = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
        elseif (!empty($div3))
            $employees = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
        elseif (!empty($div4))
            $employees = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
        elseif (!empty($div5))
            $employees = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');
			
        $historyAudit = leave_history::getLeaveHistory($actionFrom, $actionTo, $employees, $LevTypID);

        $data['historyAudit'] = $historyAudit;
        $data['page_title'] = "Leave history Audit Report";
        $data['page_description'] = "Leave history Audit Report";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Leave History Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Reports';
		$data['division_levels'] = $divisionLevels;
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

    /**
     * @param Request $request
     * @return BladeView|false|Factory|Application|View
     */
    public function cancelledLeaves(Request $request)
    {
		$this->validate($request, [
            'division_level_5' => 'required',
        ]);
        $reportData = $request->all();
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->limit(2)->get();
		//
		$div5 = !empty($reportData['division_level_5']) ? $reportData['division_level_5'] : 0;
        $div4 = !empty($reportData['division_level_4']) ? $reportData['division_level_4'] : 0;
        $div3 = !empty($reportData['division_level_3']) ? $reportData['division_level_3'] : 0;
        $div2 = !empty($reportData['division_level_2']) ? $reportData['division_level_2'] : 0;
        $div1 = !empty($reportData['division_level_1']) ? $reportData['division_level_1'] : 0;
		$userID = !empty($reportData['hr_person_id']) ? $reportData['hr_person_id'] : 0;

        if (!empty($userID))
            $employees = $userID;
        elseif (!empty($div1))
            $employees = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('hr_id');
        elseif (!empty($div2))
            $employees = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
        elseif (!empty($div3))
            $employees = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
        elseif (!empty($div4))
            $employees = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
        elseif (!empty($div5))
            $employees = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');
			
        return $this->getCancelledLeavesReport($employees, $reportData['leave_types_id'], $reportData['action_date'], false);
    }

    /**
     * @param Request $request
     * @return BladeView|false|Factory|Application|View
     */
    public function cancelledLeavesPrint(Request $request)
    {

        $reportData = $request->all();
        return $this->getCancelledLeavesReport($reportData['hr_person_id'], $reportData['leave_types_id'], $reportData['action_date'], true);
    }

    /**
     * @param $employeeID
     * @param $leaveTypeID
     * @param $action_date
     * @param $print
     * @return BladeView|false|Factory|Application|View
     */
    private function getCancelledLeavesReport($employees, $leaveTypeID, $action_date, $print = false)
    {
        $data['employees'] = $employees;
        $data['leaveTypeID'] = $leaveTypeID;
        $data['action_date'] = $action_date;
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->limit(2)->get();
        $actionFrom = $actionTo = 0;
        $employeeID = !empty($employeeID) ? (int)$employeeID : 0;
        $leaveTypeID = !empty($leaveTypeID) ? (int)$leaveTypeID : 0;
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $leaveApplications = leave_application::where('status', 10)
			->where(function ($query) use ($employees) {
				if (!empty($employees)) {
						$query->whereIn('hr_id', $employees);
				}
			})
            ->where(function ($query) use ($leaveTypeID) {
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
			
		$data['division_levels'] = $divisionLevels;
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
        } else {
            AuditReportsController::store('Leave Management', 'Generate Cancelled Leaves Report', "Generated by user", 0);
            return view('leave.reports.cancelled_leaves_report')->with($data);
        }
    }

    /**
     * @param Request $request
     * @return BladeView|false|Factory|Application|View
     */
    public function leavebalance(Request $request)
    {
        $this->validate($request, [
            'division_level_5' => 'required',
        ]);
        $request = $request->all();
        unset($request['_token']);

        $LevTypID = !empty($request['leave_types_id']) ? $request['leave_types_id'] : 0;
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->limit(2)->get();
		//
		$div5 = !empty($request['division_level_5']) ? $request['division_level_5'] : 0;
        $div4 = !empty($request['division_level_4']) ? $request['division_level_4'] : 0;
        $div3 = !empty($request['division_level_3']) ? $request['division_level_3'] : 0;
        $div2 = !empty($request['division_level_2']) ? $request['division_level_2'] : 0;
        $div1 = !empty($request['division_level_1']) ? $request['division_level_1'] : 0;
		$userID = !empty($request['hr_person_id']) ? $request['hr_person_id'] : 0;

        if (!empty($userID))
            $employees = $userID;
        elseif (!empty($div1))
            $employees = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('hr_id');
        elseif (!empty($div2))
            $employees = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
        elseif (!empty($div3))
            $employees = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
        elseif (!empty($div4))
            $employees = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
        elseif (!empty($div5))
            $employees = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');
			//return $employees;
        #Query the leave credit
        $credit = leave_history::getLeaveBalance($employees, $LevTypID);

        $data['userID'] = $userID;
        $data['LevTypID'] = $LevTypID;
        $data['credit'] = $credit;
		$data['division_levels'] = $divisionLevels;
        $data['div1'] = $div1;
        $data['div2'] = $div2;
        $data['div3'] = $div3;
        $data['div4'] = $div4;
        $data['div5'] = $div5;
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

    /**
     * @param Request $request
     * @return BladeView|false|Factory|Application|View
     */
    public function printlevbalReport(Request $request)
    {

        $userID = $request['userID'];
        $LevTypID = $request['LevTypID'];
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->limit(2)->get();
		//
		$div5 = !empty($request['div5']) ? $request['div5'] : 0;
        $div4 = !empty($request['div4']) ? $request['div4'] : 0;
        $div3 = !empty($request['div3']) ? $request['div3'] : 0;
        $div2 = !empty($request['div2']) ? $request['div2'] : 0;
        $div1 = !empty($request['div1']) ? $request['div1'] : 0;
		$userID = !empty($request['hr_person_id']) ? $request['hr_person_id'] : 0;

        if (!empty($userID))
            $employees = $userID;
        elseif (!empty($div1))
            $employees = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('hr_id');
        elseif (!empty($div2))
            $employees = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
        elseif (!empty($div3))
            $employees = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
        elseif (!empty($div4))
            $employees = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
        elseif (!empty($div5))
            $employees = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');
			
        $credit = leave_history::getLeaveBalance($employees, $LevTypID);

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
		$data['division_levels'] = $divisionLevels;
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

    /**
     * leaveAllowance
     * @param Request $request
     * @return BladeView|false|Factory|Application|View
     */
    public function leaveAllowance(Request $request)
    {
        $this->validate($request, [
            'division_level_5' => 'required',
        ]);
        $request = $request->all();
        unset($request['_token']);

        $LevTypID = $request['leave_types_id'];
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->limit(2)->get();
		//
		$div5 = !empty($request['division_level_5']) ? $request['division_level_5'] : 0;
        $div4 = !empty($request['division_level_4']) ? $request['division_level_4'] : 0;
        $div3 = !empty($request['division_level_3']) ? $request['division_level_3'] : 0;
        $div2 = !empty($request['division_level_2']) ? $request['division_level_2'] : 0;
        $div1 = !empty($request['division_level_1']) ? $request['division_level_1'] : 0;
		$userID = !empty($request['hr_person_id']) ? $request['hr_person_id'] : 0;

        if (!empty($userID))
            $employees = $userID;
        elseif (!empty($div1))
            $employees = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('hr_id');
        elseif (!empty($div2))
            $employees = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
        elseif (!empty($div3))
            $employees = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
        elseif (!empty($div4))
            $employees = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
        elseif (!empty($div5))
            $employees = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');
			
        $allowances = leave_history::getLeaveAllowance($employees, $LevTypID);

        $data['userID'] = $userID;
        $data['LevTypID'] = $LevTypID;
        $data['allowances'] = $allowances;
		$data['division_levels'] = $divisionLevels;
        $data['div1'] = $div1;
        $data['div2'] = $div2;
        $data['div3'] = $div3;
        $data['div4'] = $div4;
        $data['div5'] = $div5;
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

    /**
     * allowance Printed
     * @param Request $request
     * @return BladeView|false|Factory|Application|View
     */
    public function leaveAllowancePrint(Request $request)
    {
        $userID = $request['hr_person_id'];
        $LevTypID = $request['leave_types_id'];
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->limit(2)->get();
		//
		$div5 = !empty($request['div5']) ? $request['div5'] : 0;
        $div4 = !empty($request['div4']) ? $request['div4'] : 0;
        $div3 = !empty($request['div3']) ? $request['div3'] : 0;
        $div2 = !empty($request['div2']) ? $request['div2'] : 0;
        $div1 = !empty($request['div1']) ? $request['div1'] : 0;
		$userID = !empty($request['hr_person_id']) ? $request['hr_person_id'] : 0;

        if (!empty($userID))
            $employees = $userID;
        elseif (!empty($div1))
            $employees = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('hr_id');
        elseif (!empty($div2))
            $employees = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
        elseif (!empty($div3))
            $employees = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
        elseif (!empty($div4))
            $employees = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
        elseif (!empty($div5))
            $employees = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');
			
        $allowances = leave_history::getLeaveAllowance($employees, $LevTypID);

        $data['allowances'] = $allowances;
        $data['page_title'] = "Leave Allowance";
        $data['page_description'] = "Report";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => '/leave/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
        ];
		$data['division_levels'] = $divisionLevels;
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

    /**
     * Leave Taken
     * @param Request $request
     * @return BladeView|false|Factory|Application|View
     */
    public function taken(Request $request)
    {
        $this->validate($request, [
           'division_level_5' => 'required',
        ]);
		
        $request = $request->all();
        unset($request['_token']);
        $actionFrom = $actionTo = 0;
        
        $LevTypID = !empty($request['leave_types_id']) ? $request['leave_types_id'] : 0;
        $actionDate = $request['action_date'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
		//$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get()->load('divisionLevelGroup');
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->limit(2)->get();
		//
		$div5 = !empty($request['division_level_5']) ? $request['division_level_5'] : 0;
        $div4 = !empty($request['division_level_4']) ? $request['division_level_4'] : 0;
        $div3 = !empty($request['division_level_3']) ? $request['division_level_3'] : 0;
        $div2 = !empty($request['division_level_2']) ? $request['division_level_2'] : 0;
        $div1 = !empty($request['division_level_1']) ? $request['division_level_1'] : 0;
		$userID = !empty($request['hr_person_id']) ? $request['hr_person_id'] : 0;
		$status = !empty($request['status']) ? $request['status'] : 0;

        if (!empty($userID))
            $employees = $userID;
        elseif (!empty($div1))
            $employees = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('hr_id');
        elseif (!empty($div2))
            $employees = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
        elseif (!empty($div3))
            $employees = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
        elseif (!empty($div4))
            $employees = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
        elseif (!empty($div5))
            $employees = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');

        $leaveTakens = leave_history::getLeaveTaken($employees, $LevTypID, $actionFrom, $actionTo,$status);
		
        $data['userID'] = $userID;
        $data['div1'] = $div1;
        $data['div2'] = $div2;
        $data['div3'] = $div3;
        $data['div4'] = $div4;
        $data['div5'] = $div5;
        $data['LevTypID'] = $LevTypID;
        $data['actionDate'] = $actionDate;
        $data['leaveTakens'] = $leaveTakens;
        $data['division_levels'] = $divisionLevels;
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

    /**
     * print taken
     * @param Request $request
     * @return BladeView|false|Factory|Application|View
     */
    public function takenPrint(Request $request)
    {

        $actionFrom = $actionTo = 0;
        $userID = !empty($request['hr_person_id']) ? $request['hr_person_id'] : 0;
        $LevTypID = !empty($request['leave_types_id']) ? $request['leave_types_id'] : 0;
        $actionDate = $request['action_date'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->limit(2)->get();
		//
		$div5 = !empty($request['div5']) ? $request['div5'] : 0;
        $div4 = !empty($request['div4']) ? $request['div4'] : 0;
        $div3 = !empty($request['div3']) ? $request['div3'] : 0;
        $div2 = !empty($request['div2']) ? $request['div2'] : 0;
        $div1 = !empty($request['div1']) ? $request['div1'] : 0;
		$userID = !empty($request['hr_person_id']) ? $request['hr_person_id'] : 0;

        if (!empty($userID))
            $employees = $userID;
        elseif (!empty($div1))
            $employees = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('hr_id');
        elseif (!empty($div2))
            $employees = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
        elseif (!empty($div3))
            $employees = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
        elseif (!empty($div4))
            $employees = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
        elseif (!empty($div5))
            $employees = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');
			
		$leaveTakens = leave_history::getLeaveTaken($employees, $LevTypID, $actionFrom, $actionTo);
		
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
		$data['division_levels'] = $divisionLevels;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['user'] = $user;
        AuditReportsController::store('Leave Management', 'Viewed Leave Taken Report Results', "view Reports Results", 0);
        return view('leave.reports.leave_taken_print_report')->with($data);
    }

    /**
     * leavepaidOut
     * @param Request $request
     * @return BladeView|false|Factory|Application|View
     */
    public function leavepaidOut(Request $request)
    {
        $this->validate($request, [
            #code here ....
        ]);
        $request = $request->all();
        unset($request['_token']);

        $userID = $request['hr_person_id'];
        $LevTypID = $request['leave_types_id'];

        $custom = leave_history::getLeavePaidOut($userID, $LevTypID);

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
	
	public function pendingLeaves(Request $request)
    {
		$this->validate($request, [
            'division_level_5' => 'required',
        ]);
        $reportData = $request->all();
		
		
		//
		$div5 = !empty($reportData['division_level_5']) ? $reportData['division_level_5'] : 0;
        $div4 = !empty($reportData['division_level_4']) ? $reportData['division_level_4'] : 0;
        $div3 = !empty($reportData['division_level_3']) ? $reportData['division_level_3'] : 0;
        $div2 = !empty($reportData['division_level_2']) ? $reportData['division_level_2'] : 0;
        $div1 = !empty($reportData['division_level_1']) ? $reportData['division_level_1'] : 0;
		$userID = !empty($reportData['hr_person_id']) ? $reportData['hr_person_id'] : 0;

        if (!empty($userID))
            $employees = $userID;
        elseif (!empty($div1))
            $employees = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('hr_id');
        elseif (!empty($div2))
            $employees = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
        elseif (!empty($div3))
            $employees = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
        elseif (!empty($div4))
            $employees = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
        elseif (!empty($div5))
            $employees = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');
			
        return $this->getpendingLeavesReport($employees, $reportData['leave_types_id'], $reportData['action_date'], false, $reportData['status']);
    }

    /**
     * @param Request $request
     * @return BladeView|false|Factory|Application|View
     */
    public function pendingLeavesPrint(Request $request)
    {

        $reportData = $request->all();
        return $this->getpendingLeavesReport($reportData['hr_person_id'], $reportData['leave_types_id'], $reportData['action_date'], true, $reportData['status']);
    }

    /**
     * @param $employeeID
     * @param $leaveTypeID
     * @param $action_date
     * @param $print
     * @return BladeView|false|Factory|Application|View
     */
    private function getpendingLeavesReport($employees, $leaveTypeID, $action_date, $print = false, $status=0)
    {
		$statusArray = [1 => 'Approved',2 => 'Require managers approval ',3 => 'Require department head approval',
            4 => 'Require hr approval',5 => 'Require payroll approval',6 => 'rejected',
            7 => 'rejectd_by_department_head',8 => 'rejectd_by_hr',9 => 'rejectd_by_payroll',
            10 => 'Cancelled'];
        $data['employees'] = $employees;
        $data['leaveTypeID'] = $leaveTypeID;
        $data['action_date'] = $action_date;
        $data['status'] = $status;
        $data['statusArray'] = $statusArray;
        $actionFrom = $actionTo = 0;
        //$employeeID = !empty($employeeID) ? (int)$employeeID : 0;
        $leaveTypeID = !empty($leaveTypeID) ? (int)$leaveTypeID : 0;
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->limit(2)->get();
        $leaveApplications = leave_application::
			where(function ($query) use ($status) {
					if ($status > 0) {
						$query->where('status', $status);
					}
					else $query->whereIn('status', [2,3,4,5,]);
				})
            ->where(function ($query) use ($employees) {
				if (!empty($employees)) {
						$query->whereIn('hr_id', $employees);
				}
			})
            ->where(function ($query) use ($leaveTypeID) {
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
		$data['division_levels'] = $divisionLevels;
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Reports';
        $data['leaveApplications'] = $leaveApplications;

        if ($print) {
            AuditReportsController::store('Audit', 'Generate Cancelled Leaves Report', "Generated by user", 0);
            $companyDetails = CompanyIdentity::systemSettings();
            $data['printing_person'] = Auth::user()->person->full_name;
            $data['company_logo'] = $companyDetails['company_logo_url'];
            $data['date'] = date("d-m-Y");
            AuditReportsController::store('Leave Management', 'Printed Leaves Status Report', "Generated by user", 0);
            return view('leave.reports.pending_leaves_report_print')->with($data);
        } else {
            AuditReportsController::store('Leave Management', 'Generate Status Leaves Report', "Generated by user", 0);
            return view('leave.reports.pending_leaves_report')->with($data);
        }
    }

}