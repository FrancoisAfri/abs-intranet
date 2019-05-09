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
        ### BEGIN: FAMILY RESPONSIBILITY LEAVE ACCRUAL
        $familyLeaveTypeID = 2;
        $employees = HRPerson::where('status', 1)->get();
        foreach ($employees as $employee) {
            if (!empty($employee->date_joined) && $employee->date_joined > 0) {
				$dateJoined = date('Y-m-d',$employee->date_joined);
				// get today
				$today = date("Y-m-d");
				$otoday = new DateTime($today);
				// convert date joined
				$odateJoined = new DateTime($dateJoined);
				$diff = $odateJoined->diff($otoday);
				$years = $diff->y;
				$months = $diff->m;
				$days = $diff->d;
				if ($years == 0 && $months == 4)
				{
                    //set family leave to 3 days
                    $leaveCredit = $this->getLeaveCredit($employee->id, $familyLeaveTypeID);
                    if ($leaveCredit) {
                        $leaveCredit->leave_balance = 3 * 8;
                        $leaveCredit->update();
                    }
                    else 
					{
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

    /*public function maternity() {
	### BEGIN: Sick maternity ACCRUAL (Leave Type = 3)

         $lev = new LeaveType();
         $users = HRPerson::where('status', 1)->where('gender', 2)->pluck('id');
         foreach ($users as $empID) {
            $maternityID = 3;
             $leavebalance = leave_credit::where('hr_id', $empID)->where('leave_type_id', $maternityID)->pluck('leave_balance')->first();
             if ($leavebalance === null) {
                 $leavebalance = 0;
             }
         }
    }*/
	public function paternity() {
	### BEGIN: Sick paternity ACCRUAL (Leave Type = 9)

         $employees = HRPerson::where('status', 1)->where('gender', 1)->get();
        foreach ($employees as $employee) {

			$leaveCredit = $this->getLeaveCredit($employee->id, 9);
			if ($leaveCredit) {
				
			}
			else {
				$leaveCredit = new leave_credit();
				$leaveCredit->hr_id = $employee->id;
				$leaveCredit->leave_type_id = 9;
				$leaveCredit->leave_balance = (3 * 8);
				$leaveCredit->save();
			}
        }
    }

        # Reset family leave after a year.
    function resetFamilyLeaves() {

        $employees = HRPerson::where('status', 1)->get();
        foreach ($employees as $employee) {

			$datevalue =  date('z', mktime(0,0,0,date('n'),date('j'),date('Y')));
			if ($datevalue == 0) {

				$leaveCredit = $this->getLeaveCredit($employee->id, 2);
				if ($leaveCredit) {
					$leaveCredit->leave_balance = (3 * 8);
					$leaveCredit->update();
				}
				else {
					$leaveCredit = new leave_credit();
					$leaveCredit->hr_id = $employee->id;
					$leaveCredit->leave_type_id = 2;
					$leaveCredit->leave_balance = (3 * 8);
					$leaveCredit->save();
				}

            }
        }
    }
	
	    # Reset sick leave after a year.
    function resetSickLeaves() {
		define('SICK_LEAVE_ID', 5);
		$employees = HRPerson::where('status', 1)->get();
        foreach ($employees as $employee) {
            
			if ($employee->date_joined && $employee->date_joined > 0) {
                $dateJoined = Carbon::createFromTimestamp($employee->date_joined);
                $todayDate = Carbon::now();
                //return $dateJoined . ' --- ' . $todayDate . ' --- ' . $dateJoined->diffInMonths($todayDate);
				if (($dateJoined->diffInYears($todayDate) >= 3) && ($dateJoined->diffInYears($todayDate) % 3 == 0) && $todayDate->isBirthday($dateJoined)) {
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
	
	    # Reset paternity leave after a year.
    function resetPaternityLeaves() {

        $employees = HRPerson::where('status', 1)->where('gender', 1)->get();
        foreach ($employees as $employee) {

			$datevalue =  date('z', mktime(0,0,0,date('n'),date('j'),date('Y')));
			if ($datevalue == 0) {

				$leaveCredit = $this->getLeaveCredit($employee->id, 9);
				if ($leaveCredit) {
					$leaveCredit->leave_balance = (3 * 8);
					$leaveCredit->update();
				}
				else {
					$leaveCredit = new leave_credit();
					$leaveCredit->hr_id = $employee->id;
					$leaveCredit->leave_type_id = 9;
					$leaveCredit->leave_balance = (3 * 8);
					$leaveCredit->save();
				}

            }
        }
    }
}
