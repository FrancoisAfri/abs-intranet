<?php

namespace App\Http\Controllers;

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

class AllocateLeavedaysAnnualCronController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function execute() {
        //$lev = new LeaveType();
        $users = HRPerson::where('status', 1)->pluck('user_id');
        foreach ($users as $empID) {
            //$lev = new leave_credit();
            $AnnualLeaveTypeID = 1;
            $leaveCredit = leave_credit::where('hr_id', $empID)
                            ->where('leave_type_id', $AnnualLeaveTypeID)
                            ->first();//pluck('leave_balance')
            $leavebalance = ($leaveCredit && $leaveCredit->leave_balance > 0) ? $leaveCredit->leave_balance : 0;
            $newAnnualbalane = 1.25 * 8;
            $Annualbalance = $leavebalance + $newAnnualbalane;
            if ($leaveCredit) {
                $leaveCredit->leave_balance = $Annualbalance;
                $leaveCredit->update();
            }
            else {
                $leaveCredit = new leave_credit();
                $leaveCredit->hr_id = $empID;
                $leaveCredit->leave_type_id = $AnnualLeaveTypeID;
                $leaveCredit->leave_balance = $Annualbalance;
                $leaveCredit->save();
            }
        }

        AuditReportsController::store('Leave Annual Management', "Cron leaveAllocationAnnual Ran", "Automatic Ran by Server", 0);
    }

}
