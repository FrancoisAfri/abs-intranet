<?php

namespace App\Http\Controllers;

use App\activity;
use App\contacts_company;
use App\HRPerson;
use App\programme;
use App\projects;
use App\User;
use App\AuditTrail;
use App\EmployeeTasks;
use App\Mail\EmployeesTasksMail;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TaskManagementController extends Controller
{
    
	public function __construct()
    {
        $this->middleware('auth');
    }
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    	
	public function index()
    {
        $data['page_title'] = "Audit Report";
        $data['page_description'] = "Audit Report";
        $data['breadcrumb'] = [
            ['title' => 'Audit', 'path' => '/audit/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],
            ['title' => 'Audit', 'path' => '/audit/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Audit Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Audit';
        $data['active_rib'] = 'Audit Report';
		
		$users = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
		
		$data['users'] = $users;
		AuditReportsController::store('Task Management', 'Add Task Page Accessed', "By User", 0);
        return view('audit.audit_search')->with($data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public static function store($description='',$duedate='',$startDate='',$escalationID=0,$employeeID=0,$taskType=0
	,$orderNo=0,$libraryID=0,$priority=0,$uploadRequired=0,$meetingID=0,$inductionID=0)
    {
		//convert dates to unix time stamp
        if (!empty($duedate)) {
            $duedate = str_replace('/', '-', $duedate);
            $intduedate = strtotime($duedate);
        }
		else $intduedate = 0;
		if (!empty($startDate)) {
            $startDate = str_replace('/', '-', $startDate);
            $intstartDate = strtotime($startDate);
        }
		else $intstartDate = 0;
		$user = Auth::user();
		$EmployeeTasks = new EmployeeTasks();
		$EmployeeTasks->induction_id = $inductionID;
		$EmployeeTasks->meeting_id = $meetingID;
		$EmployeeTasks->is_dependent = ($taskType == 1) ? 1 : 0;
		$EmployeeTasks->order_no = $orderNo;
		$EmployeeTasks->added_by = $user->id;
		$EmployeeTasks->escalation_id = $escalationID;
		$EmployeeTasks->upload_required = $uploadRequired;
		$EmployeeTasks->priority = $priority;
		$EmployeeTasks->status = 1;
		$EmployeeTasks->task_type = $taskType;
		$EmployeeTasks->employee_id = $employeeID;
		$EmployeeTasks->library_id = $libraryID;
		$EmployeeTasks->description = $description;
		$EmployeeTasks->due_date = $intduedate;
		$EmployeeTasks->start_date = $intstartDate;
		// Save task
        $EmployeeTasks->save();
		
		# Send Email to employee
		$employee = HRPerson::where('id', $employeeID)->first();
		Mail::to($employee->email)->send(new EmployeesTasksMail($employee));
		AuditReportsController::store('Task Management', 'Task Successfully Added', "Added by user", 0);
		//if ($taskType == 3)
			//return redirect('/education/activity/' . $activity->id . '/view')->with('success_add', "The task has been added successfully");
    }
	// draw audit report acccording to search criteria
	public function getReport(Request $request)
    {
		$actionFrom = $actionTo = 0;
		$actionDate = $request->action_date;
		$userID = $request->user_id;
		$action = $request->action;
		$moduleName = $request->module_name;
		if (!empty($actionDate))
		{
			$startExplode = explode('-', $actionDate);
			$actionFrom = strtotime($startExplode[0]);
			$actionTo = strtotime($startExplode[1]);
		}
		$audits = DB::table('audit_trail')
		->select('audit_trail.*','hr_people.first_name as firstname', 'hr_people.surname as surname')
		->leftJoin('hr_people', 'audit_trail.user_id', '=', 'hr_people.user_id')
		->where(function ($query) use ($actionFrom, $actionTo) {
		if ($actionFrom > 0 && $actionTo  > 0) {
			$query->whereBetween('audit_trail.action_date', [$actionFrom, $actionTo]);
		}
		})
		->where(function ($query) use ($userID) {
		if (!empty($userID)) {
			$query->where('audit_trail.user_id', $userID);
		}
		})
		->where(function ($query) use ($moduleName) {
			if (!empty($moduleName)) {
				$query->where('audit_trail.module_name', 'ILIKE', "%$moduleName%");
			}
		})
		->where(function ($query) use ($action) {
			if (!empty($action)) {
				$query->where('audit_trail.action', 'ILIKE', "%$action%");
			}
		})
		->orderBy('audit_trail.module_name')
		->get();
        $data['action'] = $request->action;
        $data['module_name'] = $request->module_name;
        $data['user_id'] = $request->user_id;
        $data['action_date'] = $request->action_date;
        $data['audits'] = $audits;
		$data['page_title'] = "Audit Report";
        $data['page_description'] = "Audit Report";
        $data['breadcrumb'] = [
            ['title' => 'Audit', 'path' => '/audit/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],
            ['title' => 'Audit', 'path' => '/audit/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Audit Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Audit';
        $data['active_rib'] = 'Audit Report';
		AuditReportsController::store('Audit', 'View Audit Search Results', "view Audit Results", 0);
        return view('audit.audit_results')->with($data);
    }
	// Print audit report acccording to sent criteria
	public function printreport(Request $request)
    {
		$actionFrom = $actionTo = 0;
		$actionDate = $request->action_date;
		$userID = $request->user_id;
		$action = $request->action;
		$moduleName = $request->module_name;
		if (!empty($actionDate))
		{
			$startExplode = explode('-', $actionDate);
			$actionFrom = strtotime($startExplode[0]);
			$actionTo = strtotime($startExplode[1]);
		}
		$audits = DB::table('audit_trail')
		->select('audit_trail.*','hr_people.first_name as firstname', 'hr_people.surname as surname')
		->leftJoin('hr_people', 'audit_trail.user_id', '=', 'hr_people.user_id')
		->where(function ($query) use ($actionFrom, $actionTo) {
		if ($actionFrom > 0 && $actionTo  > 0) {
			$query->whereBetween('audit_trail.action_date', [$actionFrom, $actionTo]);
		}
		})
		->where(function ($query) use ($userID) {
		if (!empty($userID)) {
			$query->where('audit_trail.user_id', $userID);
		}
		})
		->where(function ($query) use ($moduleName) {
			if (!empty($moduleName)) {
				$query->where('audit_trail.module_name', 'ILIKE', "%$moduleName%");
			}
		})
		->where(function ($query) use ($action) {
			if (!empty($action)) {
				$query->where('audit_trail.action', 'ILIKE', "%$action%");
			}
		})
		->orderBy('audit_trail.module_name')
		->get();
		
        $data['audits'] = $audits;   
        $data['page_title'] = "Audit Report";
        $data['page_description'] = "Audit Report";
        $data['breadcrumb'] = [
            ['title' => 'Audit', 'path' => '/audit/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],
            ['title' => 'Audit', 'path' => '/audit/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Audit Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Audit';
        $data['active_rib'] = 'Audit Report';
		$user = Auth::user()->load('person');
		$data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'OSIZWENI EDUCATIONAL AND DEVELOPMENT \TRUST';
        $data['company_logo'] = url('/') . Storage::disk('local')->url('logos/logo.jpg');
		$data['date'] = date("d-m-Y");
		$data['user'] = $user;
		//return $data;
		AuditReportsController::store('Audit', 'Print Audit Search Results', "Print Audit Results", 0);
        return view('audit.audit_print')->with($data);
    }
}