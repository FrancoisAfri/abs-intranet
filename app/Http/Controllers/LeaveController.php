<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Users;
use Illuminate\Support\Facades\DB;
use App\modules;
use App\LeaveType;
use App\module_access;
use App\module_ribbons;
use App\ribbons_access;

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
        
        $leaveTypes = DB::table('leave_types')->orderBy('name', 'asc')->get();
		$data['page_title'] = "leave Setup";
		$data['page_description'] = "Admin page for leave related settings";
		$data['breadcrumb'] = [
			['title' => 'Security', 'path' => '/leave/types', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
			['title' => '', 'active' => 1, 'is_module' => 0]
		];
		$data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Leave Types';
		$data['leaveTypes'] = $leaveTypes;
        //return $leaveTypes;
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
           // 'description' => 'required',
            //'font_awesome' => 'required',
        
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
 
}