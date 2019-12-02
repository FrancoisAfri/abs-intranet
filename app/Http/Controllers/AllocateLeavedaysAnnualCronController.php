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
use App\LeaveAllocation;
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
        $users = HRPerson::where('status', 1)->pluck('id');
        foreach ($users as $empID) {
			
			$AnnualLeaveTypeID = 1;
			// check if this leave have already been allocated for this month
			$allocation = LeaveAllocation::where('hr_id', $empID)
				->where('leave_type_id', $AnnualLeaveTypeID)
				->where('month_allocated', date('n'))
				->first();
			if (empty($allocation))
			{
				$customDays = $days = $maximum = 0;
				// get Custom leave if there is any
				$custLeave = leave_custom::where('hr_id', $empID)->first();
				if (!empty($custLeave->id) && $custLeave->number_of_days > 0) {
					$customDays = $custLeave->number_of_days;
					$customDays = ($customDays / 12) * 8;
				}
				// return leave profile id based on an user id;
				// get min value from pivot
				#get leaveprofile ID
				$LevProfID = HRPerson::where('id', $empID)->first();
				if (!empty($LevProfID))
				{
					$proId = $LevProfID->leave_profile;
					$minimum = type_profile::where('leave_type_id', $AnnualLeaveTypeID)
							->where('leave_profile_id', $proId)
							->first();
		
					if (count($minimum) > 0) {
						if (!empty($minimum->min))
							$days = ($minimum->min / 12) * 8;
						if (!empty($minimum->max))
							$maximum = $minimum->max * 8;
					}
				}

				if (!empty($customDays)) $days = $customDays;
				if (!empty($days))
				{
					$credits = leave_credit::where('hr_id', $empID)
						->where('leave_type_id', $AnnualLeaveTypeID)
						->first();
					

					if (count($credits) > 0)
					{
						$previousBalance = !empty($credits->leave_balance) ? $credits->leave_balance : 0;
						$currentBalance =  $previousBalance + $days;
						$currentBalance =  $currentBalance;
						$previousBalance =  $previousBalance;
						if ($maximum > $currentBalance)
						{
							$credits->leave_balance = $currentBalance;
							$credits->update();
							LeaveHistoryAuditController::store('leave days allocation','leave days allocation', $previousBalance ,$days,$currentBalance,$AnnualLeaveTypeID,$empID);
							//insert into allocation table
							$leaveAllocation = new LeaveAllocation();
							$leaveAllocation->hr_id = $empID;
							$leaveAllocation->month_allocated = date('n');
							$leaveAllocation->leave_type_id = $AnnualLeaveTypeID;
							$leaveAllocation->allocated_by = $user->person->id;;
							$leaveAllocation->date_allocated = time();
							$leaveAllocation->balance_before = $previousBalance;
							$leaveAllocation->current_balance = $currentBalance;
							$leaveAllocation->save();
						}
					}
					else
					{
						$previousBalance = 0;
						$currentBalance = $days;
						$credit = new leave_credit();
						$credit->leave_balance = $days;
						$credit->hr_id = $empID;
						$credit->leave_type_id = $AnnualLeaveTypeID;
						$credit->save();
						LeaveHistoryAuditController::store('leave days allocation','leave days allocation', 0 ,$days,$currentBalance,$AnnualLeaveTypeID,$empID);
						//insert into allocation table
						$leaveAllocation = new LeaveAllocation();
						$leaveAllocation->hr_id = $empID;
						$leaveAllocation->month_allocated = date('n');
						$leaveAllocation->leave_type_id = $AnnualLeaveTypeID;
						$leaveAllocation->allocated_by = $user->person->id;;
						$leaveAllocation->date_allocated = time();
						$leaveAllocation->balance_before = $previousBalance;
						$leaveAllocation->current_balance = $currentBalance;
						$leaveAllocation->save();
					}
				}
			}
        }
        AuditReportsController::store('Leave Annual Management', "Cron leaveAllocationAnnual Ran", "Automatic Ran by Server", 0);
    }
}