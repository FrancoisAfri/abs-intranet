<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Request;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AllocateLeavedaysFamilyCronController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function execute() {
        
		 ### BEGIN: FAMILY RESPONSIBILITY LEAVE ACCRUAL (Leave Type = 2)
		
		$DefaultFamily = 0;
	    $lev = new LeaveType();
        $users = HRPerson::where('status', 1)->pluck('user_id');
        foreach ($users as $empID) {
			$currentDate = time(); //current_date today
			
			$iStartCredits = 0;
            $lev = new leave_credit();
            $FamilyLeaveTypeID = 2;
            $leavebalance = leave_credit::where('hr_id', $empID)
                            ->where('leave_type_id', $FamilyLeaveTypeID)
                            ->pluck('leave_balance')->first();
            if ($leavebalance == null) {
                $leavebalance = 0;
            }
		 //$leavebalance * 8;
		 
        
    }

}
