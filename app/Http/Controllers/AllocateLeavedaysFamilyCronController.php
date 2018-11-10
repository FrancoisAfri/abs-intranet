<?php

namespace App\Http\Controllers;

use App\leave_application;
use Carbon\Carbon;
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
        $familyLeaveTypeID = 2;
        $employees = HRPerson::where('status', 1)->get();
        foreach ($employees as $employee) {
            if ($employee->date_joined && $employee->date_joined > 0) {
                $dateJoined = Carbon::createFromTimestamp($employee->date_joined);
                $todayDate = Carbon::now();
                if ($todayDate->isBirthday($dateJoined)) {
                    //set family leave to 3 days
                    $leaveCredit = $this->getLeaveCredit($employee->id, $familyLeaveTypeID);
                    if ($leaveCredit) {
                        $leaveCredit->leave_balance = 3 * 8;
                        $leaveCredit->update();
                    }
                    else {
                        $leaveCredit = new leave_credit();
                        $leaveCredit->hr_id = $employee->id;
                        $leaveCredit->leave_type_id = $familyLeaveTypeID;
                        $leaveCredit->leave_balance = 3 * 8;
                        $leaveCredit->save();
                    }
                }
            }
        }
    }

    public function sickDays() {
        ### BEGIN: Sick LEAVE ACCRUAL (Leave Type = 5)
        define('SICK_LEAVE_ID', 5);
        define('TOTAL_SICK_LEAVE_DAYS', 30);
        $employees = HRPerson::where('status', 1)->get();
        foreach ($employees as $employee) {
            if ($employee->date_joined && $employee->date_joined > 0) {
                $dateJoined = Carbon::createFromTimestamp($employee->date_joined);
                $todayDate = Carbon::now();
                //return $dateJoined . ' --- ' . $todayDate . ' --- ' . $dateJoined->diffInMonths($todayDate);
                if ($dateJoined->diffInMonths($todayDate) < 6) {
                    //give one day sick leave for every 26 weekdays
                    if (($dateJoined->diffInWeekdays($todayDate) >= 26) && ($dateJoined->diffInWeekdays($todayDate) % 26 == 0)) {
                        //dd($employee->full_name);
                        $leaveCredit = $this->getLeaveCredit($employee->id, SICK_LEAVE_ID);
                        if ($leaveCredit) {
                            $leaveCredit->leave_balance += 8;
                            $leaveCredit->update();
                        }
                        else {
                            $leaveCredit = new leave_credit();
                            $leaveCredit->hr_id = $employee->id;
                            $leaveCredit->leave_type_id = SICK_LEAVE_ID;
                            $leaveCredit->leave_balance = 8;
                            $leaveCredit->save();
                        }
                    }
                }
                elseif ($dateJoined->diffInMonths($todayDate) >= 6 && $dateJoined->diffInYears($todayDate) < 3) {
                    //give them the rest of the leave days from the initial total of 30
                    $sixMonthsLater = $dateJoined->copy()->addMonths(6);
                    $yesterday = $todayDate->copy()->subDay();
                    if ($sixMonthsLater->isToday()) {
                        //dd($employee->full_name);
                        $remLeaveDaysInCycle = TOTAL_SICK_LEAVE_DAYS - (intdiv($dateJoined->diffInWeekdays($yesterday), 26));
                        $leaveCredit = $this->getLeaveCredit($employee->id, SICK_LEAVE_ID);
                        if ($leaveCredit) {
                            $leaveCredit->leave_balance += ($remLeaveDaysInCycle * 8);
                            $leaveCredit->update();
                        }
                        else {
                            $leaveCredit = new leave_credit();
                            $leaveCredit->hr_id = $employee->id;
                            $leaveCredit->leave_type_id = SICK_LEAVE_ID;
                            $leaveCredit->leave_balance = ($remLeaveDaysInCycle * 8);
                            $leaveCredit->save();
                        }
                    }
                }
                elseif (($dateJoined->diffInYears($todayDate) >= 3) && ($dateJoined->diffInYears($todayDate) % 3 == 0) && $todayDate->isBirthday($dateJoined)) {
                    //reset sick leave balance to 30 days
                    $leaveCredit = $this->getLeaveCredit($employee->id, SICK_LEAVE_ID);
                    if ($leaveCredit) {
                        $leaveCredit->leave_balance = (30 * 8);
                        $leaveCredit->update();
                    }
                    else {
                        $leaveCredit = new leave_credit();
                        $leaveCredit->hr_id = $employee->id;
                        $leaveCredit->leave_type_id = SICK_LEAVE_ID;
                        $leaveCredit->leave_balance = (30 * 8);
                        $leaveCredit->save();
                    }
                }
            }
        }
    }

    /**
     * Helper function to return an employee's leave balance record
     *
     * @param $employeeID
     * @param $leaveTypeID
     * @return mixed
     */
    private function getLeaveCredit($employeeID, $leaveTypeID)
    {
        $leaveCredit = leave_credit::where('hr_id', $employeeID)
            ->where('leave_type_id', $leaveTypeID)
            ->first();
        return $leaveCredit;
    }

    // public function maternity() {

    //     ### BEGIN: Sick maternity ACCRUAL (Leave Type = 3)

    //     $lev = new LeaveType();
    //     $users = HRPerson::where('status', 1)->pluck('id');
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
//    function resetLeaves() {
//
//        $users = HRPerson::where('status', 1)->pluck('user_id');
//        foreach ($users as $empID) {
//
//            $FamilyLeaveTypeID = 2;
//
//            $currentDate = time(); //current_date today
//
//            ###USER DATEhIRED
//            $dateofhire = HRPerson::where('user_id', $empID)->pluck('date_joined')->first();
//            if ($dateofhire == null) {
//                $dateofhire = 0;
//            }
//
//            ### Resert days only if its users annivesary
//            if ((date('d', $dateofhire) == date('d', $currentDate)) && (date('n', $currentDate) == date('n', $dateofhire)) && (date('Y', $currentDate) - date('Y', $dateofhire) + 1)) {
//
//                $lev = new LeaveType();
//                $leaveBal = 3 * 8;
//                $lev->hr_id = $empID;
//                $lev->leave_balance = $leaveBal;
//                $lev->leave_type_id = $FamilyLeaveTypeID;
//                $lev->create_at = time();
//                $lev->update();
//
//            } elseif ((date('d', $dateofhire) == date('d', $currentDate)) && (date('n', $currentDate) == date('n', $dateofhire)) && (date('Y', $currentDate) - date('Y', $dateofhire) + 3)) {
//
//                $SickID = 5;
//                $lev = new LeaveType();
//                $leaveBal = 3 * 8;
//                $lev->hr_id = $empID;
//                $lev->leave_balance = $SickID;
//                $lev->leave_type_id = $FamilyLeaveTypeID;
//                $lev->create_at = time();
//                $lev->update();
//
//            }
//        }
//    }
}
