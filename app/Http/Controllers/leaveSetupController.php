<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Controllers\LeaveHistoryAuditController;
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
use App\leave_profile;
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
        $data['page_description'] = "leave types";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => '/leave/types', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
             ['title' => 'Leave Types ', 'active' => 1, 'is_module' => 0]
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
            ['title' => 'Leave Management', 'path' => '/leave/Allocate_leave_types', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],['title' => 'Allocate leave ', 'active' => 1, 'is_module' => 0]
        ];
        $leaveTypes = DB::table('leave_types')->orderBy('name', 'asc')->get();
        $leave_profile = DB::table('leave_profile')->orderBy('name', 'asc')->get();
        $leaveTypes = DB::table('leave_types')->orderBy('name', 'asc')->get();
        $leave_credit = DB::table('leave_credit')->orderBy('id', 'asc')->get();

       $employees = HRPerson::where('status', 1)->get();
        
        $leveType = LeaveType::where('status',1)->get()->load(['leave_profle'=>function($query){
          $query->orderBy('name', 'asc');  
        }]);
        
//        return $leveType;
        
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
            ['title' => 'Leave Management', 'path' => '/leave/setup', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
            $leaveTypes = LeaveType::orderBy('name', 'asc')->get()->load(['leave_profle' => function($query) {
            $query->orderBy('id', 'asc');

        }]);

        $type_profile = DB::table('type_profile')->orderBy('min', 'asc')->get();
        $leave_configuration = DB::table('leave_configuration')->where("id", 1)->get()->first();
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

   

    public function addAnnual(Request $request, $id) {
         $this->validate($request, [    
         'number_of_days_annual' => 'required|numeric',  
            // 'leave_type' => 'bail|required',       
            // 'division_level_2' => 'bail|required',       
            // 'division_level_1' => 'bail|required',       
            // 'hr_person_id' => 'bail|required',
            // 'resert_days' => 'bail|required', 
                 
        ]);
           $lateData = $request->all();
        unset($lateData['_token']);

        $row = leave_configuration::count();
        if ($row>0) {
        DB::table('leave_configuration') ->where('id', $id) ->update($lateData);
    }
        else {
        $leave_configuration = new leave_configuration($lateData);
        $leave_configuration->save();
    }

        return response()->json();

    }

     public function addSick(Request $request, $id) {

        $this->validate($request, [
            'number_of_days_sick' => 'required|numeric',
            
        ]);
         $lateData = $request->all();
        unset($lateData['_token']);

        $row = leave_configuration::count();
        if ($row>0) {
        DB::table('leave_configuration') ->where('id', $id) ->update($lateData);
    }
        else {
        $leave_configuration = new leave_configuration($lateData);
        $leave_configuration->save();
    }
        AuditReportsController::store('Leave custom', 'leave custom Added', "Actioned Performed By User", 0);
        return response()->json();

    }

     public function  leavecredit(Request $request, HRPerson $person ,LeaveType $lev)
     {
      $this->validate($request, [    
            // 'leave_type' => 'bail|required',       
            // 'Division' => 'bail|required',       
            // 'Department' => 'bail|required',       
            // 'Employee name' => 'bail|required',
            // 'adjust_days' => 'bail|required', 
            // 'number_of_days' => 'bail|numeric|required',
        ]);

        $allData= $request->all();
        unset($allData['_token']);
    
        $employees = $allData['hr_person_id'];
        $leveTyps = $allData['leave_types_id'];
        $days = $allData['adjust_days'];
        $HRpeople = HRPerson::find($employees);
//        return $HRpeople;
        foreach ($leveTyps as $leveTyp ) {
          # code...
           foreach($employees as $empID) {
            $emp = HRPerson::find($empID);
//            $pro = $emp->leave_types;
            $profile =$emp->leave_types->where('id', $empID)->first() ;

            #$
            $row = leave_credit::where('hr_id', $empID)
                                ->where('leave_type_id',$leveTyp)
                                ->count();                         

            //return $row;
           $val = 0;

         if ($row>0) {
            $val = $profile->pivot->leave_balance;
                    }

            // calculations
            $currentBalance =  $val + ($days * 8);
            $emp->leave_types()->where('leave_type_id',$leveTyp)->sync([$empID => ['leave_balance' => $currentBalance ]]);
//         return $val;   

         AuditReportsController::store('Leave', 'leave days adjusted ', "Edited by User: $lev->name", 0);
        #LeaveHistoryAuditController::store('Added annul leave Days', 1 ,0,0,$currentBalance);
       }
        }
       
        
    return back()->with('success_application', "leave action was successful adjusted.");
}
        //leavecredit


public function  resert(Request $request, LeaveType $lev)
{
            $this->validate($request, [      
            // 'leave_type' => 'bail|required',       
            // 'Division' => 'bail|required',       
            // 'Department' => 'bail|required',       
            // 'Employee name' => 'bail|required',
            // 'resert_days' => 'bail|required', 
                 
        ]);
   

        $resertData= $request->all();
        unset($resertData['_token']);
        // return $resertData;

            $resertDays = $resertData['resert_days'];
             $hrID = $resertData['hr_person_id'];
             //return $hrID;
             $typIDs = $resertData['leave_types_id'];
            $resert_days = $resertDays * 8;
       // $employees = $resertData['hr_person_id'];
            //return $request->all();

    foreach ($typIDs as $typID ) {
        
      foreach($hrID as $empID) {
            $emp = HRPerson::find($empID);
        $emp->leave_types()->where('leave_type_id',$typID)->sync([$empID => ['leave_balance' => $resert_days]]);

         AuditReportsController::store('Leave', 'leave days reset Edited', "Edited by User: $lev->name", 0);
         // LeaveHistoryAuditController::store("leave days reseted ", 1 ,0,0,$resert_days);
        }
    }
        
    

    return back()->with('success_application', "leave allocation was successful resert.");
}
    
    public function allocate(Request $request , LeaveType $lev)
    {
        $this->validate($request, [      
           // 'leave_type' => 'required',       
           // 'division_level_2' => 'required',       
           //  'division_level_1' => 'required',       
           //  'hr_person_id' => 'required',
                 
        ]);
        $allData= $request->all();
        unset($allData['_token']);
       // return $allData;
        
        $empl = $allData['hr_person_id'];
        $LevIDs = $allData['leave_types_id'];
        //return $LevID;
       $days = $allData['adjust_days'];
       
       foreach($LevIDs as $LevID) {

               foreach($empl as $empID) {
            //return person records based on employee id
                $emp = HRPerson::find($empID);
                // return leave types records based on leave type id
                $leaveTyps = LeaveType::find($LevID);      
                $annul = LeaveType::find(1)->id;
                    // return leave profile from the hr person table based on employee id 
                $levPro = HRPerson::find($empID)->leave_profile;
//                   return $levPro;
            // getting the hr_id from custom leave
               //$row = leave_custom::count();
                
                 $custLeave = leave_custom::where('hr_id',$empID)->get();
                 $custLeave = $custLeave->first();
               
                $row = leave_custom::where('hr_id',$empID)->count();
               // return $row;

                $custDays = 0;
                $custstatus = 0;
                if($row > 0){
                 $custDays = $custLeave->number_of_days; 
                 $custstatus = $custLeave->status; 

                }

                
                //$custDays = $custLeave->number_of_days;
                    
               //$levcust = leave_custom::find($empID)->hr_id;

                
            //return $custom days and custom status 
           
            
            $levcustom = $custDays/12;
             $custleave = $levcustom * 8; 
                // return leave profile id based on an user id;

         
                  //$credit = $minimum->hr_person->where('id', 1)->first();
//            return $levCreditv;    
            // get min value from pivot

             #get leaveprofile ID
             $LevProfID = HRPerson::where('user_id', $empl)->first();
             $proId= $LevProfID->leave_profile;

               $minimum = type_profile::where('leave_type_id', $LevID)
                                ->where('leave_profile_id' , $proId)
                                ->first(); 

                                $min = 0;
                                if(count($minimum) > 0){
                                    
                                    $min = $minimum->min;
                                } 

               // return $minimum;
             
             //return $min;
            $mini=$min / 12;
            $min = $mini * 8 ;
        //      return $mini;
            
//            return $min;
            //$typID = $levcust && $custstaus = 0
            
                if ($LevID = $annul && $custstatus = 1 )
            {
                //$emp->leave_types()->sync([$empID => ['leave_balance' => $custleave ]]);   
                $emp->leave_types()->where('leave_type_id',$LevID)->sync([$empID => ['leave_balance' => $custleave]]);
            }
            else if($LevID != $annul )
            {
                //$emp->leave_types()->sync([$empID => ['leave_balance' => $min ]]);
                 $emp->leave_types()->where('leave_type_id',$LevID)->sync([$empID => ['leave_balance' => $min]]);
            }
        
        //
         AuditReportsController::store('Leave', 'leave days allocation Edited', "Edited by User: $lev->name", 0);
       
        }  // LeaveHistoryAuditController::store('leave days allocation', 1 ,0,1,0 );
       }
 
       
            
       return back()->with('success_application', "leave allocation was successful."); 
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
