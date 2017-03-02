<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Users;
use App\leave_custom;
use App\HRPerson;
use Illuminate\Support\Facades\DB;
use App\modules;
use App\LeaveType;
use App\module_access;
use App\module_ribbons;
use App\ribbons_access;
use Illuminate\Support\Facades\Auth;

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
		return response()->json(['name' => $leave->name, 'description' => $leave->description], 200);
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
}