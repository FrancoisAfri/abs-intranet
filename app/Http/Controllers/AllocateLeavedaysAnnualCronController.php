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

class AllocateLeavedaysAnnualCronController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function execute()
    {
		
        $users = HRPerson::where('status', 1)->pluck('id');
		
        foreach ($users as $empID) {

            $AnnualLeaveTypeID = 1;
            // check if this leave have already been allocated for this month
            $allocation = LeaveAllocation::where(
                [
                    'hr_id' => $empID,
                    'leave_type_id' => $AnnualLeaveTypeID,
                    'month_allocated' => date('n'),
                    'year_allocated' => date('Y')
                ]
            )
                ->first();
				
            if (empty($allocation)) 
			{
                $customDays = $days = $maximum = 0;
                // get Custom leave if there is any

                $custLeave = leave_custom::where('hr_id', $empID)->first();

                if (!empty($custLeave['id']) && $custLeave['number_of_days'] > 0) {
                    $customDays = $custLeave['number_of_days'];
                    $customDays = ($customDays / 12) * 8;
                }
                // return leave profile id based on an user id;
                // get min value from pivot
                #get leaveprofile ID
                $LevProfID = HRPerson::getDirectorDetails($empID);

                if (!empty($LevProfID['leave_profile'])) {

                    $minimum = type_profile::where(
                        [
                            'leave_type_id' => $AnnualLeaveTypeID,
                            'leave_profile_id' => $LevProfID['leave_profile']
                        ]
                    )->first();

                    if (!empty($minimum)) {
                        if (!empty($minimum['min']))
                            $days = ($minimum['min'] / 12) * 8;
                        if (!empty($minimum['max']))
                            $maximum = $minimum['max'] * 8;
                    }

                }

                if (!empty($customDays)) $days = $customDays;
                if (!empty($days)) {
                    $credits = leave_credit::where(
                        [
                            'hr_id' => $empID,
                            'leave_type_id' => $AnnualLeaveTypeID
                        ]
                    )->first();

                    if (!empty($credits)) {
						
                        $previousBalance = !empty($credits->leave_balance) ? $credits->leave_balance : 0;
						$currentBalance = $previousBalance + $days;
                        $currentBalance = $currentBalance;
                        if ($maximum > $currentBalance) {
                            $credits->leave_balance = $currentBalance;
                            $credits->update();
                            LeaveHistoryAuditController::store('leave days allocation', 'leave days allocation', $previousBalance, $days, $currentBalance, $AnnualLeaveTypeID, $empID,1,0);
                            //insert into allocation table

                            $leaveAllocation = LeaveAllocation::create(
                                [
                                    'hr_id' => $empID,
                                    'month_allocated' => date('n'),
									'year_allocated' => date('Y'),
                                    'leave_type_id' => $AnnualLeaveTypeID,
                                    'allocated_by' => 1, // change this
                                    'date_allocated' => time(),
                                    'balance_before' => $previousBalance,
                                    'current_balance' => $currentBalance,
                                ]
                            );

                        }
                    } 
					else {
                        $previousBalance = 0;
                        $currentBalance = $days;

                        $credit = leave_credit::create(
                            [
                                'leave_balance' => $days,
                                'hr_id' => $empID,
                                'leave_type_id' => $AnnualLeaveTypeID,
                            ]
                        );

                        LeaveHistoryAuditController::store('leave days allocation', 'leave days allocation', 0, $days, $currentBalance, $AnnualLeaveTypeID, $empID,1,0);

                        $leaveAllocation = LeaveAllocation::create(
                            [
                                'hr_id' => $empID,
                                'month_allocated' => date('n'),
                                'year_allocated' => date('Y'),
                                'leave_type_id' => $AnnualLeaveTypeID,
                                'allocated_by' => 1, // change this
                                'date_allocated' => time(),
                                'balance_before' => $previousBalance,
                                'current_balance' => $currentBalance,
                            ]
                        );
                    }
                }
            }
        }
        AuditReportsController::store('Leave Management', "Cron leave Allocation Annual Ran", "Automatic Ran by Server", 0);
    }
}