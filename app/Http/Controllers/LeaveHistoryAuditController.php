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
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
#
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

       public static function store($action='',$descriptionAction ='',$previousBalance='',$transcation='' ,
         $current_balance ='',$leave_type ='')
    {
        $user = Auth::user();
        $leave_history = new leave_history();
//        $leave_history
        $leave_history->hr_id = $user->id;
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
  
    #
   
   
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
    #
    #draw history report according to search critea
    public function getlevhistoryReport(Request $request)
    {
          $this->validate($request, [    
         
        ]);
           $request = $request->all();
        unset($request['_token']);
 // return $request;
     
    $actionFrom = $actionTo = 0;
    $userID = $request['hr_person_id'];
    $action = $request['action'];
    $actionDate = $request['action_date'];  
   //return $actionDate;
    if (!empty($actionDate))
    {
      $startExplode = explode('-', $actionDate);
      $actionFrom = strtotime($startExplode[0]);
      $actionTo = strtotime($startExplode[1]);
    }
    $historyAudit  = DB::table('leave_history')
    ->select('leave_history.*','hr_people.employee_number as employee_number ','hr_people.first_name as firstname', 'hr_people.surname as surname')
    ->leftJoin('hr_people', 'leave_history.hr_id', '=', 'hr_people.user_id')
     ->where(function ($query) use ($actionFrom, $actionTo) {
    if ($actionFrom > 0 && $actionTo  > 0) {
      $query->whereBetween('leave_history.action_date', [$actionFrom, $actionTo]);
    }
    })
     ->where(function ($query) use ($userID) {
    if (!empty($userID)) {
      $query->where('leave_history.hr_id', $userID);
    }
    })
    ->where(function ($query) use ($action) {
      if (!empty($action)) {
        $query->where('leave_history.action', 'ILIKE', "%$action%");
      }
    })
    ->orderBy('leave_history.hr_id')
    ->get();

    // return $historyAudit;
    #    
        $data['actionFrom'] = $actionFrom;
        $data['userID'] = $userID;
        $data['action'] = $action; 
        $data['actionDate'] = $actionDate;
        $data['historyAudit'] = $historyAudit;
        $data['page_title'] = "Leave history Audit Report";
        $data['page_description'] = "Leave history Audit Report";
        $data['breadcrumb'] = [
             ['title' => 'Leave Management', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],            //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
              ['title' => 'Leave History Audit', 'active' => 1, 'is_module' => 0]

        ];
        $data['active_mod'] = 'leave';
        $data['active_rib'] = 'Leave History Audit';
    AuditReportsController::store('Audit', 'View Audit Search Results', "view Audit Results", 0);
        return view('leave.leave_history report')->with($data);
    }

    #
  public function printlevhistoReport(Request $request){    
    $actionFrom = $actionTo = 0;
    $userID = $request['userID'];
    $action = $request['action'];
    $actionDate = $request['actionDate'];  
   //return $userID;
    if (!empty($actionDate))
    {
      $startExplode = explode('-', $actionDate);
      $actionFrom = strtotime($startExplode[0]);
      $actionTo = strtotime($startExplode[1]);
    }
    $historyAudit  = DB::table('leave_history')
    ->select('leave_history.*','hr_people.first_name as firstname', 'hr_people.surname as surname')
    ->leftJoin('hr_people', 'leave_history.hr_id', '=', 'hr_people.user_id')
     ->where(function ($query) use ($actionFrom, $actionTo) {
    if ($actionFrom > 0 && $actionTo  > 0) {
      $query->whereBetween('leave_history.action_date', [$actionFrom, $actionTo]);
    }
    })
     ->where(function ($query) use ($userID) {
    if (!empty($userID)) {
      $query->where('leave_history.hr_id', $userID);
    }
    })
    ->where(function ($query) use ($action) {
      if (!empty($action)) {
        $query->where('leave_history.action', 'ILIKE', "%$action%");
      }
    })
    ->orderBy('leave_history.hr_id')
    ->get();



    //return $historyAudit;
           $name = $historyAudit->first()->firstname;
           $surname = $historyAudit->first()->firstname;;
            
      //$negsickDays = $negativesickDays->first()->allow_sick_negative_days;
    #   

        $data['name'] = $name;
        $data['surname'] = $surname;  
        $data['action'] = $action;
        $data['user_id'] = $userID;
        $data['action_date'] = $actionDate;
        $data['historyAudit'] = $historyAudit;
        $data['page_title'] = "Leave history Audit Report";
        $data['page_description'] = "Leave history Audit Report";
        $data['breadcrumb'] = [
             ['title' => 'Leave Management', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],            //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
              ['title' => 'Leave History Audit', 'active' => 1, 'is_module' => 0]

        ];
        $data['active_mod'] = 'leave';
        $data['active_rib'] = 'Leave History Audit';
        $user = Auth::user()->load('person');
        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'OSIZWENI EDUCATIONAL AND DEVELOPMENT \TRUST';
        $data['company_logo'] = url('/') . Storage::disk('local')->url('logos/logo.jpg');
        $data['date'] = date("d-m-Y");
        AuditReportsController::store('Audit', 'View Audit Search Results', "view Audit Results", 0);
        return view('leave.reports.leave_history_print')->with($data);

    }
    // 
       public function leavebalance(Request $request){
        $this->validate($request, [    
          #code here ....
          
        ]);
           $request = $request->all();
        unset($request['_token']);

        // return $request;
            $userID = $request['hr_person_id'];
            $LevTypID = $request['leave_types_id'];
            $DivisionID =$request['division_level_2'];
            $DepartmentID =$request['division_level_1'];
            //$dateFrom = trim($request['date_from']); 
            $dateTo = trim($request['date_to']);;
            //return $dateTo;

            // 
             #Query the leave credit
      $credit  = DB::table('hr_people')
                  ->select('hr_people.*', 'leave_credit.hr_id as userID','leave_credit.leave_balance as Balance','leave_credit.leave_type_id as LeaveID','leave_types.name as leaveType')
                  ->leftJoin('leave_credit', 'leave_credit.hr_id', '=', 'hr_people.id')
                  ->leftJoin('leave_types', 'leave_credit.leave_type_id', '=', 'leave_types.id')
                  ->where('hr_people.status' ,1) 
                  ->where(function ($query) use ($userID) {
                    if (!empty($userID)) {
                      $query->where('hr_people.id', $userID);
                    }
                    })
                    ->where(function ($query) use ($LevTypID) {
                      if (!empty($LevTypID)) {
                        $query->where('leave_credit.leave_types_id', $LevTypID );
                      }
                    })
                    //->GROUPBY('leave_credit.hr_id')
                    //->having('hr_people.id', '=', 'hr_people.id')
                    ->orderBy('leave_credit.hr_id')
                    ->get();

                    // return $credit;

        $data['userID'] = $userID;
        $data['LevTypID'] = $LevTypID;
        $data['credit'] = $credit;
        $data['page_title'] = "Leave Reports";
        $data['page_description'] = "Leave Report";
        $data['breadcrumb'] = [
             ['title' => 'Leave Management', 'path' => '/leave/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],            //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
              ['title' => 'Leave Balance Audit', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'leave';
        $data['active_rib'] = 'Leave Reports';
        AuditReportsController::store('Audit', 'View Reports Search Results', "view Reports Results", 0);
        return view('leave.leave_report_balance')->with($data);
    }
    // 
    public function printlevbalReport(Request $request){
       
        $userID = $request['userID'];
        $LevTypID = $request['LevTypID'];

         $credit  = DB::table('hr_people')
                  ->select('hr_people.*', 'leave_credit.hr_id as userID','leave_credit.leave_balance as Balance','leave_credit.leave_type_id as LeaveID','leave_types.name as leaveType')
                  ->leftJoin('leave_credit', 'leave_credit.hr_id', '=', 'hr_people.id')
                  ->leftJoin('leave_types', 'leave_credit.leave_type_id', '=', 'leave_types.id')
                  ->where('hr_people.status' ,1) 
                  ->where(function ($query) use ($userID) {
                    if (!empty($userID)) {
                      $query->where('hr_people.id', $userID);
                    }
                    })
                    ->where(function ($query) use ($LevTypID) {
                      if (!empty($LevTypID)) {
                        $query->where('leave_credit.leave_types_id', $LevTypID );
                      }
                    })
                    //->GROUPBY('leave_credit.hr_id')
                    //->having('hr_people.id', '=', 'hr_people.id')
                    ->orderBy('leave_credit.hr_id')
                    ->get();



        $name = $credit->first()->first_name;
        $surname = $credit->first()->surname;

        $data['name'] = $name;
        $data['surname'] = $surname;  
        // $data['action'] = $action;
        $data['user_id'] = $userID;
         $data['LevTypID'] = $LevTypID;
        // $data['action_date'] = $actionDate;
        $data['credit'] = $credit;
        $data['page_title'] = "Leave history Audit Report";
        $data['page_description'] = "Leave history Audit Report";
        $data['breadcrumb'] = [
             ['title' => 'Leave Management', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],            //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
              ['title' => 'Leave History Audit', 'active' => 1, 'is_module' => 0]

        ];
        $data['active_mod'] = 'leave';
        $data['active_rib'] = 'Leave History Audit';
        $user = Auth::user()->load('person');
        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'OSIZWENI EDUCATIONAL AND DEVELOPMENT \TRUST';
        $data['company_logo'] = url('/') . Storage::disk('local')->url('logos/logo.jpg');
        $data['date'] = date("d-m-Y");
        AuditReportsController::store('Audit', 'View Audit Search Results', "view Audit Results", 0);
        return view('leave.leave_balance_print')->with($data);        

    }
   
     public function leaveAllowance(Request $request){
        $this->validate($request, [    
         #validation code here ....
        ]);
           $request = $request->all();
            unset($request['_token']);

         //return $request;
            $userID = $request['hr_person_id'];
            $LevTypID = $request['leave_types_id'];
            $DivisionID =$request['division_level_2'];
            $DepartmentID =$request['division_level_1'];
     
            $custom = DB::table('hr_people')                 
                        ->select('hr_people.*', 'type_profile.max as max','type_profile.leave_type_id as levID','type_profile.leave_profile_id as ProfID','leave_customs.hr_id as empID','leave_customs.number_of_days as Days','leave_types.name as leaveType')
                        ->leftJoin('type_profile', 'type_profile.leave_profile_id', '=', 'type_profile.leave_profile_id')
                        ->leftJoin('leave_customs', 'hr_people.id', '=', 'leave_customs.hr_id')
                        ->leftJoin('leave_types', 'type_profile.leave_type_id', '=', 'leave_types.id')
                        ->where('leave_customs.status' ,1)
                        ->where('hr_people.status' ,1)  
                        // ->whereRaw('type_profile.max < leave_customs.number_of_days') 
                        ->where(function ($query) use ($userID) {
                          if (!empty($userID)) {
                            $query->where('hr_people.id', $userID);
                          }
                          })
                          ->where(function ($query) use ($LevTypID) {
                            if (!empty($LevTypID)) {
                              $query->where('type_profile.leave_type_id', $LevTypID );
                            }
                          })
                            // if(){

                            // }elseif (condition) {
                            //   # code...
                            // }
                          ->orderBy('type_profile.leave_type_id')
                          ->get();
                          return $custom;


                    $data['userID'] = $userID;
                    $data['LevTypID'] = $LevTypID;
                    $data['custom'] = $custom;
                    $data['DivisionID'] = $DivisionID;
                    $data['DepartmentID'] = $DepartmentID;
                    $data['page_title'] = "Leave Reports";
                    $data['page_description'] = "Leave Report";
                    $data['breadcrumb'] = [
                         ['title' => 'Leave Management', 'path' => '/leave/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],           
                          ['title' => 'Leave Taken Audit', 'active' => 1, 'is_module' => 0]
                    ];
                    $data['active_mod'] = 'leave';
                    $data['active_rib'] = 'Leave Reports';
                    AuditReportsController::store('Audit', 'View Reports Search Results', "view Reports Results", 0);
                    return view('leave.leave_allowance report')->with($data);
                

            }
        public function taken(Request $request){
               $this->validate($request, [    
         #validation code here ....
        ]);
           $request = $request->all();
            unset($request['_token']);

         //return $request;
            $userID = $request['hr_person_id'];
            $LevTypID = $request['leave_types_id'];
            $DivisionID =$request['division_level_2'];
            $DepartmentID =$request['division_level_1'];
           # $dateFrom = trim($request['date_from']); 
           # $dateTo = trim($request['date_to']);;
            //return $dateTo;
              #leave day part for now

            // if ($dateFrom == '' && $dateTo == '') {
            //     $dateFrom = Carbon::now()->day(15)->month(1);
            //     $dateTo = Carbon::now()->day(15);
            // }
            // elseif ($dateFrom != '' && $dateTo == '') {
            //     $dateFrom = Carbon::createFromFormat('d F Y', '15 ' . $dateFrom);
            //     $dateTo = $dateFrom->copy()->month((int) date('m'));
            // }
            // elseif ($dateFrom == '' && $dateTo != '') {
            //     $dateTo = Carbon::createFromFormat('d F Y', '15 ' . $dateTo);
            //     $dateFrom = $dateTo->copy()->month(1);
            // }
            // elseif ($dateFrom != '' && $dateTo != '') {
            //     $dateFrom = Carbon::createFromFormat('d F Y', '15 ' . $dateFrom);
            //     $dateTo = Carbon::createFromFormat('d F Y', '15 ' . $dateTo);
            // }
            // $dateFrom->setTime(0, 0, 0);
            // $dateTo->setTime(0, 0, 0);
            // $printDateFrom = $dateFrom->copy();

              $custom = DB::table('hr_people')                 
                        ->select('hr_people.*', 'leave_application.hr_id as userID','leave_application.leave_type_id as leaveType','leave_types.name as leaveTypename')
                        ->leftJoin('leave_application', 'hr_people.id', '=', 'leave_application.hr_id')
                        ->leftJoin('leave_types', 'leave_application.leave_type_id', '=', 'leave_types.id')
                        ->where('hr_people.status' ,1)   
                        ->where(function ($query) use ($userID) {
                          if (!empty($userID)) {
                            $query->where('hr_people.id', $userID);
                          }
                          })
                          ->where(function ($query) use ($LevTypID) {
                            if (!empty($LevTypID)) {
                              $query->where('leave_application.leave_type_id', $LevTypID );
                            }
                          })
                            // if(){

                            // }elseif (condition) {
                            //   # code...
                            // }
                          ->orderBy('leave_application.leave_type_id')
                          ->get();
                         // return $custom;


                    $data['userID'] = $userID;
                    $data['LevTypID'] = $LevTypID;
                    $data['custom'] = $custom;
                    $data['DivisionID'] = $DivisionID;
                    $data['DepartmentID'] = $DepartmentID;
                    $data['page_title'] = "Leave Reports";
                    $data['page_description'] = "Leave Report";
                    $data['breadcrumb'] = [
                         ['title' => 'Leave Management', 'path' => '/leave/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],            //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
                          ['title' => 'Leave Taken Audit', 'active' => 1, 'is_module' => 0]
                    ];
                    $data['active_mod'] = 'leave';
                    $data['active_rib'] = 'Leave Reports';
                    AuditReportsController::store('Audit', 'View Reports Search Results', "view Reports Results", 0);
                    return view('leave.leave_taken report')->with($data);
                
            }
           
      public function leavepaidOut(Request $request){
        $this->validate($request, [    
          #code here ....
          
        ]);
           $request = $request->all();
        unset($request['_token']);

        #return $request;
        //return $request;
            $userID = $request['hr_person_id'];
            $LevTypID = $request['leave_types_id'];
            $DivisionID =$request['division_level_2'];
            $DepartmentID =$request['division_level_1'];
           # $dateFrom = trim($request['date_from']); 
           # $dateTo = trim($request['date_to']);;
            //return $dateTo;
              #leave day part for now

            // if ($dateFrom == '' && $dateTo == '') {
            //     $dateFrom = Carbon::now()->day(15)->month(1);
            //     $dateTo = Carbon::now()->day(15);
            // }
            // elseif ($dateFrom != '' && $dateTo == '') {
            //     $dateFrom = Carbon::createFromFormat('d F Y', '15 ' . $dateFrom);
            //     $dateTo = $dateFrom->copy()->month((int) date('m'));
            // }
            // elseif ($dateFrom == '' && $dateTo != '') {
            //     $dateTo = Carbon::createFromFormat('d F Y', '15 ' . $dateTo);
            //     $dateFrom = $dateTo->copy()->month(1);
            // }
            // elseif ($dateFrom != '' && $dateTo != '') {
            //     $dateFrom = Carbon::createFromFormat('d F Y', '15 ' . $dateFrom);
            //     $dateTo = Carbon::createFromFormat('d F Y', '15 ' . $dateTo);
            // }
            // $dateFrom->setTime(0, 0, 0);
            // $dateTo->setTime(0, 0, 0);
            // $printDateFrom = $dateFrom->copy();

              $custom = DB::table('hr_people')                 
                        ->select('hr_people.*', 'type_profile.max as max','type_profile.leave_type_id as levID','type_profile.leave_profile_id as ProfID','leave_customs.hr_id as empID','leave_customs.number_of_days as Days','leave_types.name as leaveType')
                        ->leftJoin('type_profile', 'type_profile.leave_profile_id', '=', 'type_profile.leave_profile_id')
                        ->leftJoin('leave_customs', 'hr_people.id', '=', 'leave_customs.hr_id')
                        ->leftJoin('leave_types', 'type_profile.leave_type_id', '=', 'leave_types.id')
                        ->where('leave_customs.status' ,1)
                        ->where('hr_people.status' ,1)   
                        ->where(function ($query) use ($userID) {
                          if (!empty($userID)) {
                            $query->where('hr_people.id', $userID);
                          }
                          })
                          ->where(function ($query) use ($LevTypID) {
                            if (!empty($LevTypID)) {
                              $query->where('type_profile.leave_type_id', $LevTypID );
                            }
                          })
                            // if(){

                            // }elseif (condition) {
                            //   # code...
                            // }
                          ->orderBy('type_profile.leave_type_id')
                          ->get();
                         // return $custom;


                    $data['userID'] = $userID;
                    $data['LevTypID'] = $LevTypID;
                    $data['custom'] = $custom;
                    $data['DivisionID'] = $DivisionID;
                    $data['DepartmentID'] = $DepartmentID;
                    $data['page_title'] = "Leave Reports";
                    $data['page_description'] = "Leave Report";
                    $data['breadcrumb'] = [
                         ['title' => 'Leave Management', 'path' => '/leave/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],            //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
                          ['title' => 'Leave Taken Audit', 'active' => 1, 'is_module' => 0]
                    ];
                    $data['active_mod'] = 'leave';
                    $data['active_rib'] = 'Leave Reports';
                    AuditReportsController::store('Audit', 'View Reports Search Results', "view Reports Results", 0);
                    return view('leave.leave_paid_out report')->with($data);

    }

    }
 