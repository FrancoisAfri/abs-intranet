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
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

    }

    public function editsetupType(Request $request, LeaveType $lev)
    {
        $this->validate($request, [
            'day5min' => 'numeric|min:2',
            'day5max' => 'numeric|min:2',
            'day5min' <= 'numeric|max:day5max',
            'day6min' => 'numeric|min:2',
            'day6max' => 'numeric|min:2',
            'shiftmin' => 'numeric|min:2',
            'shiftmax' => 'numeric|min:2',
        ]);

        $day5min = (trim($request->input('day5min')) != '') ? (int)$request->input('day5min') : null;
        $day5max = (trim($request->input('day5max')) != '') ? (int)$request->input('day5max') : null;
        $day6min = (trim($request->input('day6min')) != '') ? (int)$request->input('day6min') : null;
        $day6max = (trim($request->input('day6max')) != '') ? (int)$request->input('day6max') : null;
        $shiftmin = (trim($request->input('shiftmin')) != '') ? (int)$request->input('shiftmin') : null;
        $shiftmax = (trim($request->input('shiftmax')) != '') ? (int)$request->input('shiftmax') : null;

        $lev->leave_profle()->sync([
            2 => ['min' => $day5min, 'max' => $day5max],
            3 => ['min' => $day6min, 'max' => $day6max],
            4 => ['min' => $shiftmin, 'max' => $shiftmax]
        ]);

        AuditReportsController::store('Leave Management', 'leave days Informations Edited', "Edited by User: $lev->name", 0);
        return response()->json();
    }

    //#leave types
    public function editLeaveType(Request $request, LeaveType $lev)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $lev->name = $request->input('name');
        $lev->description = $request->input('description');
        $lev->update();
        AuditReportsController::store('Leave Management', 'leave type Informations Edited', "Edited by User: $lev->name", 0);
        return response()->json(['new_name' => $lev->name, 'description' => $lev->description], 200);
    }

    public function addleave(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $leaveData = $request->all();
        unset($leaveData['_token']);
        $leave = new LeaveType($leaveData);
        $leave->status = 1;
        $leave->save();
        AuditReportsController::store('Leave Management', 'leave type Added', "leave type Name: $leave->name", 0);
    }

    public function leaveAct(LeaveType $lev)
    {
        if ($lev->status == 1)
		{
            $stastus = 0;
			$statusDisplay = "De-activated";
		}
        else
		{
            $stastus = 1;
			$statusDisplay = "Activated";
		}
        $lev->status = $stastus;
        $lev->update();
		AuditReportsController::store('Leave Management', "leave type status changed: $statusDisplay", "leave type Name: $lev->name", 0);
        return back();
    }

    // custom leave
    public function addcustom(Request $request)
    {
        $this->validate($request, [
            'hr_id' => 'required|unique:leave_customs'  ,
            'number_of_days' => 'required',
			 
        ]);

        $leaveData = $request->all();
        unset($leaveData['_token']);
        $leave_customs = new leave_custom();
        $leave_customs->updateOrCreate(['hr_id' => $leaveData['hr_id']], ['number_of_days' => $leaveData['number_of_days'], 'status' => 1]);
        AuditReportsController::store('Leave Management', 'leave custom Added', "leave type Name: $leave_customs->hr_id", 0);
        return response()->json();
    }

//
    public function editcustomLeaveType(Request $request, leave_custom $lev)
    {
        $this->validate($request, [
            'number_of_days' => 'numeric|required',
        ]);
        $lev->number_of_days = $request->input('number_of_days');
        $lev->update();
        AuditReportsController::store('Leave Management', 'leave custom  Informations Edited', "Edited by User", 0);
        return response()->json();
    }

    //
    public function customleaveAct(leave_custom $lev)
    {
        if ($lev->status == 1)
        {
            $stastus = 0;
			$statusDisplay = "De-activated";
		}
        else
		{
            $stastus = 1;
			$statusDisplay = "Activated";
		}

        $lev->status = $stastus;
        $lev->update();
		AuditReportsController::store('Leave Management', "leave custom status changed: $statusDisplay", "leave custom: $lev->number_of_days", 0);
        return back();
    }

}
