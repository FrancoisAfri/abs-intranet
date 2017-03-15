<?php

namespace App\Http\Controllers;

use App\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Users;
use App\leave_custom;
use App\leave_configuration;
use App\HRPerson;
use App\modules;
use App\type_profile;
use App\module_access;
use App\module_ribbons;
use App\ribbons_access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class LeaveController extends Controller
{
    //
   public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        //$data['page_title'] = "Users";
//        $data['page_description'] = "Search Users";
    }

    public function types(){
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
       // return $leaveTypes;
        AuditReportsController::store('Leave', 'Leave Type Page Accessed', "Accessed By User", 0);
        return view('leave.leave_types')->with($data);
    }
    //#leave set up
    public function showSetup() {
        $leaveTypes = LeaveType::orderBy('name', 'asc')->get()->load(['leave_profle' => function($query) {
            $query->orderBy('id', 'asc');

        }]);
        //return $leaveTypes->first()->leave_profle->where('id', 3);
        $type_profile = DB::table('type_profile')->orderBy('min', 'asc')->get();
        $leave_configuration = DB::table('leave_configuration')->get();
        //$type_profile = App\LeaveType::find(1)->type_profile()->orderBy('name')->get();

       //return $type_profile;
        $employees = HRPerson::where('status', 1)->get();


        $data['page_title'] = "leave type";
        $data['page_description'] = "leave set up ";
        $data['breadcrumb'] = [
            ['title' => 'leave', 'path' => '/leave/setup', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'setup';
        $data['leave_configuration'] = $leave_configuration;
        $data['leaveTypes'] = $leaveTypes;
       // $data['leave_profle']=$leave_profle;
        $data['type_profile'] = $type_profile;
       //return $type_profile;
        $data['employees'] = $employees;

        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('leave.setup')->with($data);
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
    #validate leave config checboxes
//        public function store(Request $request,leave_configuration $levg){
//          
////            $this-> validate($request,['allow_annualLeave_credit','allow_sickLeave_credit',
////                                       'show_non_employees_in_leave_Module','require_managers_approval',
////                                       'all_managers_to_approve','require_department_head_approval',
////                                       'require_hr_approval','require_payroll_approval' => 'required']);
////            $request::get('allow_annualLeave_credit');
////           if(!Input::get)
//            
//                $levg->get($request->all());
//                return back();
//
//        }

        //#collect checkboxes from Leave CreditSettings

        public function rules(Request $request)
        {

            // $rules[
            //     'credit' => required
            //   ];

                
            // $input = Input:only('Allow_AnnualLeave_Credit','Allow_SickLeave_Credit','Show_non_employees_in_Leave_Module');
            // $leave = new Leave;
            // $leave->Allow_AnnualLeave_Credit = $Input['Allow_AnnualLeave_Credit']
            // $data = 

        }



    //#leave types
	public function editLeaveType(Request $request, LeaveType $lev)
	{
        $this->validate($request, [
            'name' => 'required',
            //'description' => 'required',
            //'font_awesome' => 'required',

        ]);

        $lev->name = $request->input('name');
        $lev->description = $request->input('description');
        //$lev->font_awesome = $request->input('font_awesome');
        $lev->update();
        return $lev;
        AuditReportsController::store('Leave', 'leavetype Informations Edited', "Edited by User: $lev->name", 0);
        return response()->json(['new_name' => $lev->name, 'description' => $lev->description], 200);
    }
	public function addleave(Request $request) {
	       $this->validate($request, [
            'name' => 'required',

        ]);

		$leaveData = $request->all();
		unset($leaveData['_token']);
		$leave = new LeaveType($leaveData);
		$leave->status = 1;
        $leave->save();
		AuditReportsController::store('leave', 'leavetype Added', "leave type Name: $leave->name", 0);
	}
    public function leaveAct(LeaveType $lev) 
    {
        if ($lev->status == 1) $stastus = 0;
        else $stastus = 1;

        $lev->status = $stastus;    
        $lev->update();
        return back();
    }
    // custom leave

    public function addcustom(Request $request) {
        $this->validate($request, [
            'hr_id' => 'required',
            'number_of_days'=> 'required',

        ]);

        $leaveData = $request->all();
        unset($leaveData['_token']);
        $leave_customs = new leave_custom($leaveData);
        $leave_customs->status = 1;
        $leave_customs->save();
        AuditReportsController::store('Leave custom', 'leave custom Added', "leave type Name: $leave_customs->hr_id", 0);
        return response()->json();
    }
//
    public function editcustomLeaveType(Request $request, leave_custom $lev)
    {
        //$user = Auth::user()->load('person');
        $this->validate($request, [
            //'hr_id' => 'required',
            'number_of_days'=>  'numeric|required',

        ]);
        //$lev->hr_id = $request->input('hr_id');
        $lev->number_of_days = $request->input('number_of_days');
        $lev->update();
        //return $lev;
        AuditReportsController::store('Leave custom', 'leave custom  Informations Edited', "Edited by User", 0);
        return response()->json();
    }
    //
    public function customleaveAct(leave_custom $lev)
    {
        if ($lev->status == 1) $stastus = 0;
        else $stastus = 1;

        $lev->status = $stastus;
        $lev->update();
        return back();
    }
//

}