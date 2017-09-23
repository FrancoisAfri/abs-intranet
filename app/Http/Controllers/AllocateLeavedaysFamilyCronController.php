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
            //employee date of hire
            $dateofhire = HRPerson::where('user_id', $empID)->pluck('date_joined')->first();
            if ($dateofhire === null) {
                $dateofhire = 0;
            }
            

            $iStartCredits = 0;

            $FamilyLeaveTypeID = 2;

            $leavebalance = leave_credit::where('hr_id', $empID)
                            ->where('leave_type_id', $FamilyLeaveTypeID)
                            ->pluck('leave_balance')->first();
            if ($leavebalance === null) {
                $leavebalance = 0;
            }

            #if leave balance is 0, the user gets 3 working days
            if ($leavebalance < 0) {
                $leaveBal = 3 * 8;
                $lev = new leave_credit();
                $lev->hr_id = $empID;
                $lev->leave_balance = $leaveBal;
                $lev->leave_type_id = $FamilyLeaveTypeID;
                $lev->create_at = strtotime(date("Y-m-d"));
                $lev->save();
            } 
        }
    }

    public function sickDays() {
        ### BEGIN: Sick LEAVE ACCRUAL (Leave Type = 5)

       
        $users = HRPerson::where('status', 1)->pluck('user_id');
        foreach ($users as $empID) {

            $SickID = 5;
            $lev = new LeaveType();

            ###USER LEAVE CREDIT
            $leavebalance = leave_credit::where('hr_id', $empID)->where('leave_type_id', $SickID)->pluck('leave_balance')->first();
            if ($leavebalance === null) {
                $leavebalance = 0;
            }

            ###USER DATEhIRED
            $dateofhire = HRPerson::where('user_id', $empID)->pluck('date_joined')->first();
            if ($dateofhire == null) {
                $dateofhire = 0;
            }

            #if Sick balance is 0, the user gets 1 working days
            if ($leavebalance < 0) {
                $leaveBal = 3 * 8;
                $lev = new leave_credit();
                $lev->hr_id = $empID;
                $lev->leave_balance = $leaveBal;
                $lev->leave_type_id = $SickID;
                $lev->create_at = time();
                $lev->save();
            }
        }
    }

    // public function maternity() {

    //     ### BEGIN: Sick maternity ACCRUAL (Leave Type = 3)

    //     $lev = new LeaveType();
    //     $users = HRPerson::where('status', 1)->pluck('user_id');
    //     foreach ($users as $empID) {

    //         $maternityID = 5;

    //         $leavebalance = leave_credit::where('hr_id', $empID)->where('leave_type_id', $maternityID)->pluck('leave_balance')->first();
    //         if ($leavebalance === null) {
    //             $leavebalance = 0;
    //         }

    //         $UserGender = HRPerson::where('user_id', $empID)->pluck('gender');
    //         if ($UserGender === null) {
    //            // ask the hr to update the user gender
    //         }
    //         ### allocate days only if the gender is female
    //         if($UserGender > 1){
    //             // 
    //         }
    //     }
    // }

        # Reset family leave after a year.
    function resetLeaves() {

        $users = HRPerson::where('status', 1)->pluck('user_id');
        foreach ($users as $empID) {

            $FamilyLeaveTypeID = 2;
            
            $currentDate = time(); //current_date today

             ###USER DATEhIRED
            $dateofhire = HRPerson::where('user_id', $empID)->pluck('date_joined')->first();
            if ($dateofhire == null) {
                $dateofhire = 0;
            }

            ### Resert days only if its users annivesary
            if ((date('d', $dateofhire) == date('d', $currentDate)) && (date('n', $currentDate) == date('n', $dateofhire)) && (date('Y', $currentDate) - date('Y', $dateofhire) + 1)) {

                 $lev = new LeaveType();
                 $leaveBal = 3 * 8;
                 $lev->hr_id = $empID;
                 $lev->leave_balance = $leaveBal;
                 $lev->leave_type_id = $FamilyLeaveTypeID;
                 $lev->create_at = time();
                 $lev->update();

            }elseif((date('d', $dateofhire) == date('d', $currentDate)) && (date('n', $currentDate) == date('n', $dateofhire)) && (date('Y', $currentDate) - date('Y', $dateofhire) + 3)) {

                 $SickID = 5;
                 $lev = new LeaveType();
                 $leaveBal = 3 * 8;
                 $lev->hr_id = $empID;
                 $lev->leave_balance = $SickID;
                 $lev->leave_type_id = $FamilyLeaveTypeID;
                 $lev->create_at = time();
                 $lev->update();

            }
       
    }
}
