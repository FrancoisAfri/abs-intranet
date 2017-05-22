<?php

namespace App\Http\Controllers;

use App\activity;
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
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class LeaveHistoryAuditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function show()
    {   

        $data['page_title'] = "Leave Audit Report";
        $data['page_description'] = "Leave History Audit";
        $data['breadcrumb'] = [
             ['title' => 'Leave Management', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],            //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
              ['title' => 'Leave History Audit', 'active' => 1, 'is_module' => 0]

        ];
        $data['active_mod'] = 'leave';
        $data['active_rib'] = 'Leave History Audit';
		
		$users = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
		$employees = HRPerson::where('status', 1)->get()->load(['leave_types' => function($query) {
            $query->orderBy('name', 'asc');
        }]);
        
        $leaveTypes = LeaveType::where('status',1)->get()->load(['leave_profle'=>function($query){
          $query->orderBy('name', 'asc');  
        }]);

        $data['leaveTypes'] = $leaveTypes;
        $data['employees'] = $employees;
		$data['users'] = $users;
		AuditReportsController::store('Leave History Audit', 'Reports page accessed', "Accessed by User", 0);
        return view('leave.leave_search')->with($data);  
    }

        public function reports()
    {   

        $data['page_title'] = "Report";
        $data['page_description'] = "Leave Reports";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => '/leave/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],['title' => 'Leave Reports', 'active' => 1, 'is_module' => 0]

        ];
        $employees = HRPerson::where('status', 1)->get()->load(['leave_types' => function($query) {
            $query->orderBy('name', 'asc');
        }]);
        
        $leaveTypes = LeaveType::where('status',1)->get()->load(['leave_profle'=>function($query){
          $query->orderBy('name', 'asc');  
        }]);

        $data['leaveTypes'] = $leaveTypes;
        $data['employees'] = $employees;
        $data['active_mod'] = 'leave';
        $data['active_rib'] = 'Leave Reports';

        
        $users = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();

          $leave_customs = leave_custom::orderBy('hr_id', 'asc')->get();
        if (!empty($leave_customs))
            $leave_customs = $leave_customs->load('userCustom');
        


        $employees = HRPerson::where('status', 1)->get()->load(['leave_types' => function($query) {
            $query->orderBy('name', 'asc');
        }]);
        
        $leaveTypes = LeaveType::where('status',1)->get()->load(['leave_profle'=>function($query){
          $query->orderBy('name', 'asc');  
        }]);
         $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
 
        
        $data['users'] = $users;
        $data['leaveTypes'] = $leaveTypes;
        $data['division_levels'] = $divisionLevels;
        $data['employees'] = $employees;
        $data['leave_customs']=$leave_customs;
        AuditReportsController::store('Leave History Audit', 'Reports page accessed', "Accessed by User", 0);
        return view('leave.reports.leave_report_index')->with($data);  
    }

public static function store($performedBy='',$descriptionAction ='',$previousBalance='',$transcation='' ,$current_balance ='')
    {
        $user = Auth::user();
        $leave_history = new leave_history();
//        $leave_history
        $leave_history->hr_id = $user->id;
        $leave_history->performed_by = $performedBy;
        $leave_history->description_action = $descriptionAction;   
        $leave_history->previous_balance = $previousBalance;  
        $leave_history->transcation = $transcation;
        $leave_history->current_balance = $current_balance;
        $leave_history->action_date = time();
        #save Audit
        $leave_history->save();    
    }
    #draw history report according to search critea
    public function getReport(Request $request)
    {

          $this->validate($request, [    
         
        ]);
           $request = $request->all();
        unset($request['_token']);

        return $request;

       // $actionFrom = $actionTo = 0;
        $action_date = $request->action_date;
        $leave_type = $request->
        $hr_person_id = $request->user_id;
       // $action = $request->action;
        //$moduleName = $request->module_name;
        if (!empty($actionDate))
        {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        return $actionTo;
       //  $audits = DB::table('audit_trail')
       //  ->select('audit_trail.*','hr_people.first_name as firstname', 'hr_people.surname as surname')
       //  ->leftJoin('hr_people', 'audit_trail.user_id', '=', 'hr_people.user_id')
       //  ->where(function ($query) use ($actionFrom, $actionTo) {
       //  if ($actionFrom > 0 && $actionTo  > 0) {
       //      $query->whereBetween('audit_trail.action_date', [$actionFrom, $actionTo]);
       //  }
       //  })
       //  ->where(function ($query) use ($userID) {
       //  if (!empty($userID)) {
       //      $query->where('audit_trail.user_id', $userID);
       //  }
       //  })
       //  ->where(function ($query) use ($moduleName) {
       //      if (!empty($moduleName)) {
       //          $query->where('audit_trail.module_name', 'ILIKE', "%$moduleName%");
       //      }
       //  })
       //  ->where(function ($query) use ($action) {
       //      if (!empty($action)) {
       //          $query->where('audit_trail.action', 'ILIKE', "%$action%");
       //      }
       //  })
       //  ->orderBy('audit_trail.module_name')
       //  ->get();
       //  $data['action'] = $request->action;
       //  $data['module_name'] = $request->module_name;
       //  $data['user_id'] = $request->user_id;
       //  $data['action_date'] = $request->action_date;
       //  $data['audits'] = $audits;
       //  $data['page_title'] = "Audit Report";
       //  $data['page_description'] = "Audit Report";
       //  $data['breadcrumb'] = [
       //      ['title' => 'Audit', 'path' => '/audit/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],
       //      ['title' => 'Audit', 'path' => '/audit/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
       //      ['title' => 'Audit Report', 'active' => 1, 'is_module' => 0]
       //  ];
       //  $data['active_mod'] = 'Audit';
       //  $data['active_rib'] = 'Audit Report';
       //  AuditReportsController::store('Audit', 'View Audit Search Results', "view Audit Results", 0);
         return view('leave.leave_results_audit')->with($data);
    // }

    }
    public function printreport(Request $request)
    {
        // $actionFrom = $actionTo = 0;
        // $actionDate = $request->action_date;
        // $userID = $request->user_id;
        // $action = $request->action;
        // $moduleName = $request->module_name;
        // if (!empty($actionDate))
        // {
        //     $startExplode = explode('-', $actionDate);
        //     $actionFrom = strtotime($startExplode[0]);
        //     $actionTo = strtotime($startExplode[1]);
        // }
    }
}
