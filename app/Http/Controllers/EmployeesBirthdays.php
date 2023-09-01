<?php

namespace App\Http\Controllers;

use App\HRPerson;
use App\programme;
use App\projects;
use App\User;
use App\AuditTrail;
use App\Mail\EmplyeesBirthdays;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EmployeesBirthdays extends Controller
{
    //
	public function execute()
    {
		$birthdays = HRPerson::select('hr_people.id'
					, 'hr_people.first_name', 'hr_people.surname'
					, 'hr_people.email'
					, 'hr_people.profile_pic'
					, 'hr_people.gender'
					,'hr_people.date_of_birth')
			->where('hr_people.status',1)
			->whereNotNull('hr_people.date_of_birth')
			->orderBy('hr_people.first_name')
			->orderBy('hr_people.surname')
			->get();
		
		//Flag employees that are on leave today
		$leaveID = 10;
		foreach ($birthdays as $employee) {
			
			$empID = $employee->id;
			
			if ((date('j',$employee->date_of_birth) === date('j')) && (date('n',$employee->date_of_birth) == date('n')))
			{
				//allocate 0.5 special leave
				
				$credits = leave_credit::where(
					[
						'hr_id' => ,$empID
						'leave_type_id' => $leaveID
					]
				)->first();

				if (!empty($credits)) {
					
					$previousBalance = !empty($credits->leave_balance) ? $credits->leave_balance : 0;
					$currentBalance = $previousBalance + 4;
			
					$credits->leave_balance = $currentBalance;
					$credits->update();
					LeaveHistoryAuditController::store('leave days allocation', 'leave birthdays days allocation', $previousBalance, 4, $currentBalance, $leaveID, $empID,1,0);
					//insert into allocation table

					$leaveAllocation = LeaveAllocation::create(
						[
							'hr_id' => $empID,
							'month_allocated' => date('n'),
							'year_allocated' => date('Y'),
							'leave_type_id' => $leaveID,
							'allocated_by' => 1, // change this
							'date_allocated' => time(),
							'balance_before' => $previousBalance,
							'current_balance' => $currentBalance,
						]
					);
				}
				else 
				{
					$previousBalance = 0;
					$currentBalance = 4;

					$credit = leave_credit::create(
						[
							'leave_balance' => 4,
							'hr_id' => $empID,
							'leave_type_id' => $leaveID,
						]
					);

					LeaveHistoryAuditController::store('leave days allocation', 'leave days allocation', 0, 4, $currentBalance, $leaveID, $empID,1,0);

					$leaveAllocation = LeaveAllocation::create(
						[
							'hr_id' => $empID,
							'month_allocated' => date('n'),
							'year_allocated' => date('Y'),
							'leave_type_id' => $leaveID,
							'allocated_by' => 1, // change this
							'date_allocated' => time(),
							'balance_before' => $previousBalance,
							'current_balance' => $currentBalance,
						]
					);
				}
				if (!empty($employee->email))
					Mail::to($employee->email)->send(new EmplyeesBirthdays($employee->first_name));
			}
		}
	}
}
