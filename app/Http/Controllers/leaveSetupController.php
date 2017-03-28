<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use App\LeaveType;
use App\Users;
use App\DivisionLevel;
use App\leave_custom;
use App\leave_configuration;
use App\HRPerson;
use App\hr_person;
use App\modules;
use App\leave_credit;
use App\leave_history;
use App\type_profile;
use App\module_ribbons;
use App\ribbons_access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;

class LeaveSetupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     
     */
    public function __construct(){

        $this->middleware('auth');
    }
    public function index()
    {
        //

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setuptypes()
    {
        //
        $leave_customs = leave_custom::orderBy('hr_id', 'asc')->get();
		if (!empty($leave_customs))
            $leave_customs = $leave_customs->load('userCustom');
        
         //return $leave_customs;
        $leaveTypes = DB::table('leave_types')->orderBy('name', 'asc')->get();
        $employees = HRPerson::where('status', 1)->get();
        $data['page_title'] = "leave Types";
        $data['page_description'] = "Admin page for leave related settings";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/leave/types', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => '', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Leave Types';
        $data['leaveTypes'] = $leaveTypes;
        $data['employees'] = $employees;
        $data['leave_customs']=$leave_customs;

        AuditReportsController::store('Leave', 'Leave Type Page Accessed', "Accessed By User", 0);
        return view('leave.leave_types')->with($data);
    }

    //#leave allocation
    public  function show()
    {   
    
        $data['page_title'] = "Allocate Leave Types";
        $data['page_description'] = "Allocate leave types ";
        $data['breadcrumb'] = [
            ['title' => 'leave', 'path' => '/leave/Allocate_leave_types', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Allocate leave ', 'active' => 1, 'is_module' => 0]
        ];
        $leaveTypes = DB::table('leave_types')->orderBy('name', 'asc')->get();
        $leave_profile = DB::table('leave_profile')->orderBy('name', 'asc')->get();
        $leaveTypes = DB::table('leave_types')->orderBy('name', 'asc')->get();
        $leave_credit = DB::table('leave_credit')->orderBy('id', 'asc')->get();

        $employees = HRPerson::where('status', 1)->get()->load(['leave_types' => function($query) {
            $query->orderBy('name', 'asc');
        }]);
        
         $leave_customs = leave_custom::orderBy('hr_id', 'asc')->get();
		if (!empty($leave_customs))
            $leave_customs = $leave_customs->load('userCustom');
        
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        //return $divisionLevels;
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Allocate Leave Types';
        $data['leaveTypes'] = $leaveTypes;
        $data['division_levels'] = $divisionLevels;
        $data['employees'] = $employees;
        $data['leave_credit'] = $leave_credit;
        //$data['$person'] = $person;
//        $data['persons'] = $persons;
        //return $leaveTypes;
        //return $employees;
        $data['leave_profile'] = $leave_profile;
        AuditReportsController::store('Leave', 'Leave Management Page Accessed', "Accessed By User", 0);
       return view('leave.leave_allocation')->with($data); 
    }
   public function showSetup(Request $request) {
       $data['page_title'] = "leave type";
        $data['page_description'] = "leave set up ";
        $data['breadcrumb'] = [
            ['title' => 'leave', 'path' => '/leave/setup', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
                $leaveTypes = LeaveType::orderBy('name', 'asc')->get()->load(['leave_profle' => function($query) {
            $query->orderBy('id', 'asc');

        }]);

        $type_profile = DB::table('type_profile')->orderBy('min', 'asc')->get();
        $leave_configuration = DB::table('leave_configuration')->get()->first();
        $employees = HRPerson::where('status', 1)->get();
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'setup';
        $data['leave_configuration'] = $leave_configuration;
        $data['leaveTypes'] = $leaveTypes;
        //$data['employees'] = $employees;
       // $data['leave_profle']=$leave_profle;
        $data['type_profile'] = $type_profile;
        $data['employees'] = $employees;
         //return $leaveTypes;
        if (isset($person['leave_profile'])) {
            $person['leave_profile'] = (int) $person['leave_profile'];
        }
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('leave.setup')->with($data);
    }

    public function  leavecredit(Request $request, HRPerson $person ){
    $validator = Validator::make($request->all(), [      
            'leave_type' => 'bail|required',       
            'division_level_2' => 'bail|required',       
            'division_level_1' => 'bail|required',       
            'hr_person_id' => 'bail|required',
            'adjust_days' => 'bail|required', 
                 
        ]);

        $allData= $request->all();
        unset($allData['_token']);
    
        $employees = $allData['hr_person_id'];
        $leaveType = $allData['leave_type'];
        $days = $allData['adjust_days'];
        $HRpeople = HRPerson::find($employees);
//        return $HRpeople;
        foreach($employees as $empID) {
            $emp = HRPerson::find($empID);
            $pro = $emp->leave_types;
            $profile =$pro->where('id', $empID)->first() ;
            $val = $profile->pivot->leave_balance;
           
            // calculations
            $currentBalance =  $val + $days;
            $emp->leave_types()->sync([$empID => ['leave_balance' => $currentBalance ]]);
//         return $val;
             
        }
//        $emp;
//      $HRpeople = HRPerson::find(1);
        
         //loop through the pivot table using the empID s
//        foreach ($emp->leave_types as $types) { 
//            $val = $types->pivot->leave_balance;
//            $currentBalance =  $val + $days  ;
//          $emp->leave_types()->sync([$empID => ['leave_balance' => $currentBalance ]
//
//        ]);
//        }

    return back();
}
        //leavecredit

public function  resert(Request $request)
{
    $validator = Validator::make($request->all(), [      
            'leave_type' => 'bail|required',       
            'division_level_2' => 'bail|required',       
            'division_level_1' => 'bail|required',       
            'hr_person_id' => 'bail|required',
            'resert_days' => 'bail|required', 
                 
        ]);

        $resertData= $request->all();
        unset($resertData['_token']);
    
        $employees = $resertData['hr_person_id'];
    
        foreach($employees as $empID) {
            $emp = HRPerson::find($empID);
//           return $emp;
        $emp->leave_types()->sync([
        $empID => ['leave_balance' => $resertData['resert_days']]

        ]);
        }
    

    return back();
}
    
    public function allocate(Request $request)
    {
        $this->validate($request, [      
//           'leave_type' => 'bail|required',       
////            'division_level_2' => 'bail|required',       
////            'division_level_1' => 'bail|required',       
//            'hr_person_id' => 'bail|required',
                 
        ]);

        
        
        $resertData= $request->all();
        unset($resertData['_token']);
        
           
         $employees = $resertData['hr_person_id'];
         foreach($employees as $empID) {
            $emp = HRPerson::find($empID);
            $custType = leave_custom::find($empID);
            $leaType = LeaveType::find($empID);
//             return ;
        }
        // loop through the pivot table to get leaveType id
     foreach ($emp->leave_types as $types) { 
            $val = $types->pivot->leave_type_id;
//         return $val;
        }
        
         $profile = $leaType->leave_profle->where('id', 2)->first() ? $profile->pivot->max : '';
        
            return $profile;

//        foreach ($leaType->leave_profile as $profile) { 
//            $prof = $profile->pivot->leave_type_id;
//            return $prof;
//        }
        
       

       return view('leave.leave_types'); 
    }
    
        
    public function editsetupType(Request $request, LeaveType $lev)
    {
        $this->validate($request, [
            'day5min'=>'numeric|min:2',
            'day5max'=>'numeric|min:2',
            'day6min'=>'numeric|min:2',
            'day6max'=>'numeric|min:2',
            'shiftmin'=>'numeric|min:2',
            'shiftmax'=>'numeric|min:2',
        ]);
        
        $day5min = (trim($request->input('day5min')) != '') ? (int) $request->input('day5min') : null;
        $day5max = (trim($request->input('day5max')) != '') ? (int) $request->input('day5max') : null;
        
        $day6min= (trim($request->input('day6min')) != '') ? (int) $request->input('day6min') : null;
        $day6max = (trim($request->input('day6max')) != '') ? (int) $request->input('day6max') : null;
        
        $shiftmin = (trim($request->input('shiftmin')) != '') ? (int) $request->input('shiftmin') : null;
        $shiftmax = (trim($request->input('shiftmax')) != '') ? (int) $request->input('shiftmax') : null;
        
        $lev->leave_profle()->sync([
            2 => ['min' => $day5min, 'max' =>$day5max],
            3 => ['min' => $day6min, 'max' => $day6max],
            4 => ['min' => $shiftmin, 'max' => $shiftmax]
        ]);
//      
        //return $lev;
        AuditReportsController::store('Leave', 'leave days Informations Edited', "Edited by User: $lev->name", 0);
        return response()->json();
    } 
  //#validate checkboxes
    public function store(Request $request,leave_configuration $levg){
         $this->validate($request, [
        ]);
            $leavecredit = $request->all();
        unset($leavecredit['_token']);
                $levg->update($leavecredit);
     //return $leavecredit;
            return back();
        }
}
